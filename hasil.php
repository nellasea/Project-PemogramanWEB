<?php
session_start();
include 'components/session_protected.php';
include 'config/koneksi.php';

$fitur = isset($_GET['fitur']) ? $_GET['fitur'] : 'jadwal';
$user_id = $_SESSION['user_id'];

// Ambil data profil dengan pengecekan yang lebih aman
$profile = null;
$profile_query = "SELECT * FROM user_profiles WHERE user_id = $user_id";
$profile_result = mysqli_query($koneksi, $profile_query);

if ($profile_result && mysqli_num_rows($profile_result) > 0) {
    $profile = mysqli_fetch_assoc($profile_result);
}

$page_title = "";
$content = "";

// Definisikan API key di sini agar bisa diakses oleh semua function
$GEMINI_API_KEY = 'AIzaSyDowJh2paqK3WJyFYahGB6gmPhE2SugLt8';

switch ($fitur) {
    case 'jadwal':
        $page_title = "Jadwal Olahraga";
        $content = generate_jadwal_content($profile);
        break;
    case 'tracker':
        $page_title = "Tracker Berat Badan";
        $content = generate_tracker_content($koneksi, $user_id);
        break;
    case 'nutrisi':
        $page_title = "Rekomendasi Nutrisi";
        $content = generate_nutrisi_content($profile);
        break;
    case 'ai':
        $page_title = "AI Assistant";
        $content = generate_ai_content($profile, $GEMINI_API_KEY);
        break;
    default:
        $page_title = "Jadwal Olahraga";
        $content = generate_jadwal_content($profile);
}

mysqli_close($koneksi);

function generate_jadwal_content($profile)
{
    $jadwal = "";

    if (!$profile) {
        return "<div class='alert alert-warning'>
                    <h3><i class='fas fa-exclamation-triangle'></i> Profil Belum Lengkap</h3>
                    <p>Silakan lengkapi profil terlebih dahulu untuk mendapatkan jadwal olahraga yang personalized.</p>
                    <a href='tambah.php' class='btn btn-primary'><i class='fas fa-user-edit'></i> Lengkapi Profil Sekarang</a>
                </div>";
    }

    // Generate jadwal berdasarkan tujuan dan tingkat aktivitas
    $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $olahraga = [];
    $icons = ['🏃‍♂️', '💪', '🧘‍♀️', '⚡', '🏊‍♂️', '🚴‍♀️', '😴'];

    switch ($profile['tingkat_aktivitas']) {
        case 'pemula':
            $olahraga = ['Cardio Ringan 30 menit', 'Istirahat', 'Strength Dasar 20 menit', 'Istirahat', 'Cardio Ringan 30 menit', 'Yoga/Peregangan 25 menit', 'Istirahat'];
            break;
        case 'menengah':
            $olahraga = ['Cardio 45 menit', 'Strength Training 30 menit', 'Cardio 45 menit', 'Strength Training 30 menit', 'Istirahat', 'HIIT 20 menit', 'Yoga/Aktif Recovery 30 menit'];
            break;
        case 'aktif':
            $olahraga = ['Strength Training 45 menit', 'Cardio Intensitas Tinggi 40 menit', 'Strength Training 45 menit', 'Cardio 40 menit', 'Strength Training 45 menit', 'HIIT 25 menit', 'Aktif Recovery 30 menit'];
            break;
        case 'sangat_aktif':
            $olahraga = ['Strength Training 60 menit', 'Cardio Intensitas Tinggi 50 menit', 'Strength Training 60 menit', 'Cardio Intensitas Tinggi 50 menit', 'Strength Training 60 menit', 'HIIT 30 menit', 'Aktif Recovery/Cardio Ringan 40 menit'];
            break;
        default:
            $olahraga = ['Istirahat', 'Istirahat', 'Istirahat', 'Istirahat', 'Istirahat', 'Istirahat', 'Istirahat'];
    }

    $jadwal .= "
    <div class='info-card'>
        <h4><i class='fas fa-info-circle'></i> Informasi Jadwal</h4>
        <p>Jadwal ini disesuaikan dengan tingkat aktivitas <strong>" . ucfirst($profile['tingkat_aktivitas']) . "</strong> dan tujuan <strong>" . ucfirst(str_replace('_', ' ', $profile['tujuan_olahraga'])) . "</strong></p>
    </div>
    
    <div class='schedule-grid'>";

    for ($i = 0; $i < 7; $i++) {
        $is_rest = strpos(strtolower($olahraga[$i]), 'istirahat') !== false;
        $card_class = $is_rest ? 'rest-day' : 'workout-day';

        $jadwal .= "
        <div class='schedule-day {$card_class}'>
            <div class='day-header'>
                <span class='day-icon'>{$icons[$i]}</span>
                <h4>{$hari[$i]}</h4>
            </div>
            <div class='workout-info'>
                <p>{$olahraga[$i]}</p>
            </div>
        </div>";
    }
    $jadwal .= "</div>";

    return $jadwal;
}

function generate_tracker_content($koneksi, $user_id)
{
    $content = "
    <div class='tracker-section'>
        <div class='tracker-form-card'>
            <h3><i class='fas fa-plus-circle'></i> Tambahkan Data Berat Badan</h3>
            <form action='logic/create_data.php' method='POST' class='tracker-form'>
                <input type='hidden' name='action' value='tracker'>
                <div class='form-group'>
                    <label for='berat_badan_tracker'><i class='fas fa-weight'></i> Berat Badan (kg)</label>
                    <input type='number' id='berat_badan_tracker' name='berat_badan' min='30' max='200' step='0.1' required placeholder='Contoh: 65.5'>
                </div>
                <div class='form-group'>
                    <label for='tanggal'><i class='far fa-calendar'></i> Tanggal</label>
                    <input type='date' id='tanggal' name='tanggal' required value='" . date('Y-m-d') . "'>
                </div>
                <button type='submit' class='btn btn-primary'><i class='fas fa-save'></i> Simpan Data</button>
            </form>
        </div>
    </div>";

    // Ambil data tracker
    $tracker_query = "SELECT * FROM weight_tracker WHERE user_id = $user_id ORDER BY tanggal DESC";
    $tracker_result = mysqli_query($koneksi, $tracker_query);

    if ($tracker_result && mysqli_num_rows($tracker_result) > 0) {
        $content .= "
        <div class='tracker-history'>
            <h3><i class='fas fa-history'></i> Riwayat Berat Badan</h3>
            <div class='table-container'>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Berat Badan</th>
                            <th>Perubahan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>";

        $previous_weight = null;
        $rows = [];
        while ($row = mysqli_fetch_assoc($tracker_result)) {
            $rows[] = $row;
        }

        // Reverse the array to process from oldest to newest for correct change calculation
        $rows = array_reverse($rows);

        foreach ($rows as $index => $row) {
            $change_html = "<span class='neutral'><i class='fas fa-minus'></i> -</span>";

            if ($index > 0) {
                $previous_weight = $rows[$index - 1]['berat_badan'];
                $current_weight = $row['berat_badan'];
                $change = $current_weight - $previous_weight;

                if ($change > 0) {
                    // Berat badan NAIK - panah ke atas, warna merah
                    $change_html = "<span class='negative'><i class='fas fa-arrow-up'></i> +" . number_format($change, 1) . " kg</span>";
                } elseif ($change < 0) {
                    // Berat badan TURUN - panah ke bawah, warna hijau
                    $change_html = "<span class='positive'><i class='fas fa-arrow-down'></i> " . number_format($change, 1) . " kg</span>";
                }
            }

            $content .= "
                    <tr>
                        <td><i class='far fa-calendar'></i> " . date('d M Y', strtotime($row['tanggal'])) . "</td>
                        <td><strong>{$row['berat_badan']} kg</strong></td>
                        <td>{$change_html}</td>
                        <td>
                            <a href=\"#\" onclick=\"confirmDelete({$row['id']})\" class=\"btn btn-danger\">
                                <i class=\"fas fa-trash\"></i> Hapus
                            </a>
                        </td>
                    </tr>";
        }

        $content .= "
                    </tbody>
                </table>
            </div>
        </div>";
    } else {
        $content .= "
        <div class='tracker-history'>
            <h3><i class='fas fa-history'></i> Riwayat Berat Badan</h3>
            <div class='empty-state'>
                <i class='fas fa-weight'></i>
                <p>Belum ada data tracker. Mulai tambahkan data pertama Anda!</p>
            </div>
        </div>";
    }

    return $content;
}
function generate_nutrisi_content($profile)
{
    if (!$profile) {
        return "<div class='alert alert-warning'>
                    <h3><i class='fas fa-exclamation-triangle'></i> Profil Belum Lengkap</h3>
                    <p>Silakan lengkapi profil terlebih dahulu untuk mendapatkan rekomendasi nutrisi yang personalized.</p>
                    <a href='tambah.php' class='btn btn-primary'><i class='fas fa-user-edit'></i> Lengkapi Profil Sekarang</a>
                </div>";
    }

    $nutrisi = "";

    // Rekomendasi nutrisi berdasarkan tujuan
    switch ($profile['tujuan_olahraga']) {
        case 'menurunkan_berat_badan':
            $nutrisi = "
            <div class='nutrition-header'>
                <h3><i class='fas fa-apple-alt'></i> Rekomendasi Nutrisi untuk Menurunkan Berat Badan</h3>
                <p>Program nutrisi yang difokuskan pada defisit kalori dengan tetap mempertahankan massa otot</p>
            </div>
            
            <div class='nutrition-grid'>
                <div class='nutrition-card'>
                    <div class='card-icon'>
                        <i class='fas fa-balance-scale'></i>
                    </div>
                    <h4>Prinsip Utama</h4>
                    <ul>
                        <li><i class='fas fa-check'></i> Defisit kalori 300-500 kalori per hari</li>
                        <li><i class='fas fa-check'></i> Tinggi protein (1.6-2.2g per kg berat badan)</li>
                        <li><i class='fas fa-check'></i> Karbohidrat kompleks sebagai sumber energi</li>
                        <li><i class='fas fa-check'></i> Lemak sehat dalam jumlah moderat</li>
                        <li><i class='fas fa-check'></i> Banyak serat dari sayuran</li>
                    </ul>
                </div>
                
                <div class='nutrition-card'>
                    <div class='card-icon'>
                        <i class='fas fa-utensils'></i>
                    </div>
                    <h4>Contoh Menu Harian</h4>
                    <div class='menu-item'>
                        <strong>🍳 Sarapan:</strong> Oatmeal dengan buah beri + 2 butir telur rebus
                    </div>
                    <div class='menu-item'>
                        <strong>🥗 Makan Siang:</strong> Salad dengan 150g dada ayam + dressing minyak zaitun
                    </div>
                    <div class='menu-item'>
                        <strong>🍲 Makan Malam:</strong> 150g ikan bakar + sayuran kukus + ubi jalar kecil
                    </div>
                    <div class='menu-item'>
                        <strong>🥛 Cemilan:</strong> Greek yogurt atau segenggam kacang almond
                    </div>
                </div>
            </div>";
            break;

        case 'meningkatkan_massa_otot':
            $nutrisi = "
            <div class='nutrition-header'>
                <h3><i class='fas fa-dumbbell'></i> Rekomendasi Nutrisi untuk Meningkatkan Massa Otot</h3>
                <p>Program nutrisi untuk mendukung pertumbuhan otot dan pemulihan setelah latihan</p>
            </div>
            
            <div class='nutrition-grid'>
                <div class='nutrition-card'>
                    <div class='card-icon'>
                        <i class='fas fa-chart-line'></i>
                    </div>
                    <h4>Prinsip Utama</h4>
                    <ul>
                        <li><i class='fas fa-check'></i> Surplus kalori 200-500 kalori per hari</li>
                        <li><i class='fas fa-check'></i> Tinggi protein (1.8-2.5g per kg berat badan)</li>
                        <li><i class='fas fa-check'></i> Karbohidrat cukup untuk energi latihan</li>
                        <li><i class='fas fa-check'></i> Lemak sehat untuk produksi hormon</li>
                        <li><i class='fas fa-check'></i> Waktu makan yang strategis</li>
                    </ul>
                </div>
                
                <div class='nutrition-card'>
                    <div class='card-icon'>
                        <i class='fas fa-utensils'></i>
                    </div>
                    <h4>Contoh Menu Harian</h4>
                    <div class='menu-item'>
                        <strong>🍳 Sarapan:</strong> 3 butir telur orak-arik + 2 slice roti gandum + alpukat
                    </div>
                    <div class='menu-item'>
                        <strong>🍚 Makan Siang:</strong> Nasi merah + 200g daging sapi + brokoli
                    </div>
                    <div class='menu-item'>
                        <strong>🐟 Makan Malam:</strong> 200g salmon + ubi jalar + asparagus
                    </div>
                    <div class='menu-item'>
                        <strong>💪 Cemilan:</strong> Protein shake, keju cottage, buah-buahan
                    </div>
                </div>
            </div>";
            break;

        default:
            $nutrisi = "
            <div class='nutrition-header'>
                <h3><i class='fas fa-heart'></i> Rekomendasi Nutrisi Seimbang</h3>
                <p>Program nutrisi untuk menjaga kesehatan dan kebugaran secara menyeluruh</p>
            </div>
            
            <div class='nutrition-grid'>
                <div class='nutrition-card'>
                    <div class='card-icon'>
                        <i class='fas fa-balance-scale'></i>
                    </div>
                    <h4>Prinsip Utama</h4>
                    <ul>
                        <li><i class='fas fa-check'></i> Keseimbangan makronutrien yang optimal</li>
                        <li><i class='fas fa-check'></i> Protein cukup (1.2-1.6g per kg berat badan)</li>
                        <li><i class='fas fa-check'></i> Karbohidrat kompleks sebagai energi utama</li>
                        <li><i class='fas fa-check'></i> Lemak sehat untuk fungsi tubuh</li>
                        <li><i class='fas fa-check'></i> Variasi makanan yang beragam</li>
                    </ul>
                </div>
                
                <div class='nutrition-card'>
                    <div class='card-icon'>
                        <i class='fas fa-utensils'></i>
                    </div>
                    <h4>Contoh Menu Harian</h4>
                    <div class='menu-item'>
                        <strong>🥣 Sarapan:</strong> Smoothie bowl dengan buah, granola, dan kacang
                    </div>
                    <div class='menu-item'>
                        <strong>🍚 Makan Siang:</strong> Quinoa bowl dengan sayuran dan protein
                    </div>
                    <div class='menu-item'>
                        <strong>🍗 Makan Malam:</strong> Ayam panggang dengan berbagai sayuran
                    </div>
                    <div class='menu-item'>
                        <strong>🍎 Cemilan:</strong> Buah segar, yogurt, atau segenggam kacang
                    </div>
                </div>
            </div>";
    }

    return $nutrisi;
}

function generate_ai_content($profile, $api_key)
{
    $profile_info = "";
    $user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

    // System prompt yang lebih terstruktur
    $system_prompt = "Anda adalah asisten kesehatan digital yang ramah dan membantu. 

**Karakter Anda:**
- Teman curhat yang santai dan supportive
- Pakai bahasa Indonesia sehari-hari yang natural
- Tambahkan emoji yang sesuai 🎉
- Panggil user dengan 'kamu' atau nama mereka
- Beri semangat dan dukungan yang tulus
- Ceritakan contoh pengalaman nyata yang relevan
- Jangan terlalu formal atau kaku

**Format Respons:**
- Gunakan paragraf pendek yang mudah dibaca
- Berikan tips praktis dan actionable
- Tanyakan follow-up question untuk melanjutkan obrolan
- Gunakan bullet points untuk poin-poin penting";

    if ($profile) {
        $bmi = $profile['bmi'];
        $bmi_category = '';
        if ($bmi < 18.5) $bmi_category = 'Kurus';
        elseif ($bmi < 25) $bmi_category = 'Normal (Ideal)';
        elseif ($bmi < 30) $bmi_category = 'Gemuk';
        else $bmi_category = 'Obesitas';

        $profile_info = "
        <div class='profile-card'>
            <h4><i class='fas fa-user-circle'></i> Profil Kesehatan Anda</h4>
            <div class='profile-grid'>
                <div class='profile-item'>
                    <i class='fas fa-birthday-cake'></i>
                    <div>
                        <span class='label'>Usia</span>
                        <span class='value'>{$profile['umur']} tahun</span>
                    </div>
                </div>
                <div class='profile-item'>
                    <i class='fas fa-ruler-vertical'></i>
                    <div>
                        <span class='label'>Tinggi</span>
                        <span class='value'>{$profile['tinggi_badan']} cm</span>
                    </div>
                </div>
                <div class='profile-item'>
                    <i class='fas fa-weight'></i>
                    <div>
                        <span class='label'>Berat</span>
                        <span class='value'>{$profile['berat_badan']} kg</span>
                    </div>
                </div>
                <div class='profile-item'>
                    <i class='fas fa-calculator'></i>
                    <div>
                        <span class='label'>BMI</span>
                        <span class='value'>" . number_format($bmi, 1) . " ($bmi_category)</span>
                    </div>
                </div>
                <div class='profile-item'>
                    <i class='fas fa-bullseye'></i>
                    <div>
                        <span class='label'>Tujuan</span>
                        <span class='value'>" . ucfirst(str_replace('_', ' ', $profile['tujuan_olahraga'])) . "</span>
                    </div>
                </div>
                <div class='profile-item'>
                    <i class='fas fa-running'></i>
                    <div>
                        <span class='label'>Aktivitas</span>
                        <span class='value'>" . ucfirst($profile['tingkat_aktivitas']) . "</span>
                    </div>
                </div>
            </div>
        </div>";

        $system_prompt .= "

**Informasi Profil User:**
- Usia: {$profile['umur']} tahun
- Tinggi: {$profile['tinggi_badan']} cm
- Berat: {$profile['berat_badan']} kg  
- BMI: " . number_format($bmi, 1) . " ($bmi_category)
- Tujuan: " . ucfirst(str_replace('_', ' ', $profile['tujuan_olahraga'])) . "
- Aktivitas: " . ucfirst($profile['tingkat_aktivitas']) . "

**Panduan Respons:**
1. Berikan saran yang personalized berdasarkan profil di atas
2. Sesuaikan rekomendasi dengan usia, BMI, dan tujuan user
3. Gunakan nama '{$user_name}' sesekali untuk personal touch
4. Berikan tips yang realistic dan achievable
5. Fokus pada kesehatan holistik (fisik & mental)";
    } else {
        $profile_info = "
        <div class='alert alert-warning'>
            <h4><i class='fas fa-exclamation-triangle'></i> Profil Belum Lengkap</h4>
            <p>Lengkapi profil Anda untuk mendapatkan saran yang lebih personalized dari AI Assistant.</p>
            <a href='tambah.php' class='btn btn-primary'><i class='fas fa-user-edit'></i> Lengkapi Profil</a>
        </div>";

        $system_prompt .= "\n\nUser belum melengkapi profil kesehatan. Berikan saran umum tentang kesehatan dengan bahasa yang santai dan menyenangkan, dan dorong mereka untuk melengkapi profil.";
    }

    // Quick questions yang lebih relevan
    $quick_questions = "
    <div class='quick-questions'>
        <h4><i class='fas fa-bolt'></i> Pertanyaan Cepat:</h4>
        <div class='quick-buttons'>
            <button onclick='askQuickQuestion(\"Bagaimana cara memulai olahraga untuk pemula?\")' class='btn-quick'>
                <i class='fas fa-running'></i> Olahraga Pemula
            </button>
            <button onclick='askQuickQuestion(\"Apa makanan sehat yang mudah dibuat?\")' class='btn-quick'>
                <i class='fas fa-utensils'></i> Makanan Sehat
            </button>
            <button onclick='askQuickQuestion(\"Tips mengelola stress sehari-hari\")' class='btn-quick'>
                <i class='fas fa-brain'></i> Kelola Stress
            </button>
            <button onclick='askQuickQuestion(\"Bagaimana menjaga motivasi hidup sehat?\")' class='btn-quick'>
                <i class='fas fa-trophy'></i> Tips Motivasi
            </button>
        </div>
    </div>";

    return "
    <div class='ai-assistant'>
        <div class='ai-header'>
            <h3><i class='fas fa-robot'></i> Asisten Kesehatan AI 🤗</h3>
            <p>Hai! Saya di sini untuk membantu perjalanan kesehatan Anda dengan saran yang personalized.</p>
        </div>
        
        {$profile_info}
        
        <div class='chat-container'>
            <div class='chat-messages' id='chatMessages'>
                <div class='message bot-message'>
                    <div class='message-header'>
                        <i class='fas fa-robot'></i>
                        <span>Asisten Kesehatan</span>
                    </div>
                    <div class='message-content'>
                        <p><strong>Halo! 👋 Senang bertemu dengan Anda!</strong></p>
                        <p>Saya di sini untuk membantu Anda dengan:</p>
                        <p>• 💪 <strong>Rekomendasi olahraga</strong> yang sesuai dengan kondisi Anda<br>
                           • 🍎 <strong>Saran nutrisi</strong> dan pola makan sehat<br>
                           • 😊 <strong>Tips kesehatan mental</strong> dan manajemen stress<br>
                           • 🎯 <strong>Strategi mencapai tujuan</strong> kesehatan Anda</p>
                        <p><strong>Ada yang bisa saya bantu hari ini?</strong> 😄</p>
                    </div>
                </div>
            </div>
            <div class='chat-input'>
                <form id='chatForm'>
                    <div class='input-group'>
                        <input type='text' id='userInput' placeholder='Tanyakan tentang kesehatan, olahraga, atau pola makan...' required>
                        <button type='submit' class='btn btn-primary'>
                            <i class='fas fa-paper-plane'></i> Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {$quick_questions}
        
        <div class='ai-info'>
            <p><i class='fas fa-info-circle'></i> <strong>Tips:</strong> Ajukan pertanyaan spesifik untuk mendapatkan saran yang lebih tepat sesuai profil Anda.</p>
        </div>
    </div>
    
    <script>
    const GEMINI_API_KEY = '" . $api_key . "';
    const SYSTEM_PROMPT = `" . addslashes($system_prompt) . "`;
    const USER_NAME = '" . addslashes($user_name) . "';

    document.getElementById('chatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const userInput = document.getElementById('userInput');
        const message = userInput.value.trim();
        
        if (!message) return;
        
        // Tambahkan pesan user ke chat
        addMessageToChat('user', message);
        userInput.value = '';
        
        // Tampilkan typing indicator
        showTypingIndicator();
        
        try {
            const response = await fetch('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' + GEMINI_API_KEY, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    contents: [{
                        parts: [{
                            text: SYSTEM_PROMPT + '\\n\\nPertanyaan User: ' + message + '\\n\\nJawab dengan bahasa Indonesia yang santai dan helpful:'
                        }]
                    }],
                    generationConfig: {
                        temperature: 0.7,
                        maxOutputTokens: 1000,
                        topP: 0.8,
                        topK: 40
                    }
                })
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            
            const data = await response.json();
            
            // Hapus typing indicator
            removeTypingIndicator();
            
            if (data.candidates && data.candidates[0] && data.candidates[0].content) {
                const botResponse = data.candidates[0].content.parts[0].text;
                addMessageToChat('bot', botResponse);
            } else {
                throw new Error('Invalid API response format');
            }
            
        } catch (error) {
            console.error('Error:', error);
            removeTypingIndicator();
            
            const fallbackResponse = getFriendlyFallbackResponse(message);
            addMessageToChat('bot', fallbackResponse);
        }
    });
    
    function getFriendlyFallbackResponse(message) {
        const lowerMessage = message.toLowerCase();
        const namePart = USER_NAME ? ', ' + USER_NAME + '!' : '!';
        
        if (lowerMessage.includes('olahraga') || lowerMessage.includes('exercise') || lowerMessage.includes('fitness')) {
            return 'Wah, topik olahraga nih! 💪' + namePart + '\\n\\nBeberapa tips olahraga yang bisa saya bagikan:\\n\\n• **Mulai bertahap** - Jangan langsung intensitas tinggi\\n• **Pilih yang disukai** - Lebih mudah konsisten\\n• **Perhatikan bentuk** - Hindari cedera\\n• **Istirahat cukup** - Penting untuk recovery\\n\\nAda jenis olahraga spesifik yang ingin Anda tanyakan?';
        } 
        else if (lowerMessage.includes('makan') || lowerMessage.includes('diet') || lowerMessage.includes('nutrisi')) {
            return 'Topik nutrisi selalu menarik! 🍎' + namePart + '\\n\\nPrinsip dasar makan sehat:\\n\\n• **Variasi makanan** - Semua kelompok makanan penting\\n• **Porsi seimbang** - Tidak berlebihan atau kurang\\n• **Hidrasi cukup** - Minum air yang cukup\\n• **Makan mindful** - Perhatikan sinyal lapar dan kenyang\\n\\nAda pertanyaan spesifik tentang pola makan?';
        } 
        else if (lowerMessage.includes('stress') || lowerMessage.includes('cemas') || lowerMessage.includes('lelah')) {
            return 'Memahami kondisi Anda 😔' + namePart + '\\n\\nBeberapa cara mengelola stress:\\n\\n• **Tarik napas dalam** - Menenangkan sistem saraf\\n• **Aktivitas fisik ringan** - Melepaskan endorfin\\n• **Time management** - Kurangi beban berlebihan\\n• **Social support** - Cerita dengan orang terpercaya\\n\\nMau cerita lebih detail tentang apa yang Anda alami?';
        }
        else {
            return 'Terima kasih atas pertanyaannya' + namePart + ' 😊\\n\\nSebagai asisten kesehatan, saya bisa membantu dengan:\\n• 💪 Rekomendasi olahraga\\n• 🍎 Saran nutrisi\\n• 😊 Tips kesehatan mental\\n• 🎯 Strategi mencapai tujuan kesehatan\\n\\nAda area spesifik yang ingin Anda diskusikan?';
        }
    }
    
    function askQuickQuestion(question) {
        document.getElementById('userInput').value = question;
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
    
    function addMessageToChat(sender, message) {
        const chatMessages = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + sender + '-message';
        
        const messageHeader = document.createElement('div');
        messageHeader.className = 'message-header';
        
        const icon = document.createElement('i');
        icon.className = sender === 'user' ? 'fas fa-user' : 'fas fa-robot';
        
        const span = document.createElement('span');
        span.textContent = sender === 'user' ? 'Anda' : 'Asisten Kesehatan';
        
        messageHeader.appendChild(icon);
        messageHeader.appendChild(span);
        
        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        
        if (sender === 'user') {
            const p = document.createElement('p');
            p.textContent = message;
            messageContent.appendChild(p);
        } else {
            formatBotMessage(message, messageContent);
        }
        
        messageDiv.appendChild(messageHeader);
        messageDiv.appendChild(messageContent);
        chatMessages.appendChild(messageDiv);
        
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function formatBotMessage(message, container) {
        const lines = message.split('\\n');
        
        lines.forEach((line, index) => {
            if (line.trim() === '' && index === lines.length - 1) return;
            
            const p = document.createElement('p');
            
            if (line.includes('**')) {
                processBoldText(line, p);
            } else {
                p.textContent = line;
            }
            
            container.appendChild(p);
        });
    }
    
    function processBoldText(text, container) {
        const boldRegex = /\\*\\*(.*?)\\*\\*/g;
        let lastIndex = 0;
        let match;
        
        while ((match = boldRegex.exec(text)) !== null) {
            if (match.index > lastIndex) {
                const textNode = document.createTextNode(text.slice(lastIndex, match.index));
                container.appendChild(textNode);
            }
            
            const strong = document.createElement('strong');
            strong.textContent = match[1];
            container.appendChild(strong);
            
            lastIndex = match.index + match[0].length;
        }
        
        if (lastIndex < text.length) {
            const textNode = document.createTextNode(text.slice(lastIndex));
            container.appendChild(textNode);
        }
    }
    
    function showTypingIndicator() {
        const chatMessages = document.getElementById('chatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'message bot-message typing';
        
        const messageHeader = document.createElement('div');
        messageHeader.className = 'message-header';
        
        const icon = document.createElement('i');
        icon.className = 'fas fa-robot';
        
        const span = document.createElement('span');
        span.textContent = 'Asisten Kesehatan';
        
        messageHeader.appendChild(icon);
        messageHeader.appendChild(span);
        
        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        
        const p = document.createElement('p');
        p.innerHTML = 'Mengetik<span class=\"typing-dots\">...</span>';
        
        messageContent.appendChild(p);
        typingDiv.appendChild(messageHeader);
        typingDiv.appendChild(messageContent);
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    document.getElementById('userInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }
    });
    
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - SehatinAja.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: linear-gradient(135deg, #5ac8fa, #9d4edd, #ff88dc);
            background-size: 300% 300%;
            animation: gradientMove 12s ease infinite;
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding-top: 30px;
        }

        /* Header Styles */
        header {
            padding: 20px 0;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            z-index: -1;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            font-size: 2rem;
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }

        header h1 {
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #ffffff, #e0c3fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .user-info span {
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.9;
        }

        /* Button Styles */
        .btn {
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn-primary {
            background: linear-gradient(45deg, #5ac8fa, #4a8fe7);
            color: #001f3f;
            box-shadow: 0 5px 15px rgba(90, 200, 250, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #ff88dc, #e86ed0);
            color: #3a003f;
            box-shadow: 0 5px 15px rgba(255, 136, 220, 0.4);
        }

        .btn-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        /* Main Content */
        main {
            padding: 40px 0;
        }

        /* Feature Detail Section */
        .feature-detail {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 25px;
            backdrop-filter: blur(15px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .feature-detail::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(90, 200, 250, 0.1), rgba(255, 136, 220, 0.1));
            z-index: -1;
        }

        .feature-detail h2 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Alert Styles */
        .alert {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .alert-warning h3 {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-warning h3 i {
            color: #ffc107;
        }

        /* Info Card */
        .info-card {
            background: rgba(255, 255, 255, 0.08);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-card h4 {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Schedule Grid */
        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 2rem;
        }

        .schedule-day {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .schedule-day:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .workout-day {
            border-left: 4px solid #5ac8fa;
        }

        .rest-day {
            border-left: 4px solid #9d4edd;
            opacity: 0.8;
        }

        .day-header {
            margin-bottom: 15px;
        }

        .day-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }

        .schedule-day h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .workout-info p {
            opacity: 0.9;
            line-height: 1.5;
        }

        /* Tracker Section */
        .tracker-section {
            margin-bottom: 3rem;
        }

        .tracker-form-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .tracker-form-card h3 {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tracker-form {
            max-width: 400px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .form-group input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Tracker History */
        .tracker-history {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .tracker-history h3 {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: rgba(255, 255, 255, 0.1);
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        td {
            opacity: 0.9;
        }

        .positive {
            color: #2ed573;
            font-weight: 600;
        }

        .negative {
            color: #ff4757;
            font-weight: 600;
        }

        .neutral {
            color: rgba(255, 255, 255, 0.6);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            opacity: 0.7;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Nutrition Styles */
        .nutrition-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .nutrition-header h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .nutrition-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .nutrition-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .nutrition-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-align: center;
            background: linear-gradient(45deg, #5ac8fa, #ff88dc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nutrition-card h4 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.3rem;
        }

        .nutrition-card ul {
            list-style: none;
        }

        .nutrition-card li {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .nutrition-card li i {
            color: #5ac8fa;
            margin-top: 2px;
        }

        .menu-item {
            margin-bottom: 15px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border-left: 3px solid #5ac8fa;
        }

        /* AI Assistant Styles */
        .ai-assistant {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .ai-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .ai-header h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.08);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-card h4 {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .profile-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .profile-item i {
            font-size: 1.2rem;
            color: #5ac8fa;
            width: 20px;
        }

        .profile-item .label {
            font-size: 0.9rem;
            opacity: 0.7;
            display: block;
        }

        .profile-item .value {
            font-weight: 600;
            display: block;
        }

        .chat-container {
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 2rem;
            background: rgba(255, 255, 255, 0.05);
        }

        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.02);
        }

        .message {
            margin-bottom: 20px;
            max-width: 85%;
        }

        .user-message {
            margin-left: auto;
        }

        .bot-message {
            margin-right: auto;
        }

        .message-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .message-content {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 20px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-message .message-content {
            background: linear-gradient(45deg, #5ac8fa, #4a8fe7);
            border-bottom-right-radius: 5px;
        }

        .bot-message .message-content {
            background: rgba(255, 255, 255, 0.1);
            border-bottom-left-radius: 5px;
        }

        .message-content p {
            margin-bottom: 8px;
        }

        .message-content p:last-child {
            margin-bottom: 0;
        }

        .typing {
            opacity: 0.7;
        }

        .typing-dots {
            animation: typing 1.5s infinite;
        }

        @keyframes typing {

            0%,
            20% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .chat-input {
            padding: 20px;
            background: rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .chat-input input {
            flex: 1;
            padding: 15px 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 1rem;
            outline: none;
        }

        .chat-input input:focus {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
        }

        .chat-input input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .quick-questions {
            margin-bottom: 2rem;
        }

        .quick-questions h4 {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .btn-quick {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 12px 15px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-quick:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .ai-info {
            background: rgba(255, 255, 255, 0.08);
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 4px solid #5ac8fa;
            font-size: 0.9rem;
        }

        .ai-info p {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-detail {
            animation: fadeIn 1s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            header h1 {
                font-size: 1.8rem;
            }

            .feature-detail {
                padding: 25px;
            }

            .feature-detail h2 {
                font-size: 2rem;
            }

            .schedule-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .nutrition-grid {
                grid-template-columns: 1fr;
            }

            .chat-messages {
                height: 300px;
            }

            .message {
                max-width: 90%;
            }

            .quick-buttons {
                grid-template-columns: 1fr;
            }

            .input-group {
                flex-direction: column;
            }

            .chat-input input {
                border-radius: 10px;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 95%;
            }

            header h1 {
                font-size: 1.6rem;
            }

            .feature-detail {
                padding: 20px;
            }

            .feature-detail h2 {
                font-size: 1.8rem;
            }

            .schedule-grid {
                grid-template-columns: 1fr;
            }

            .chat-messages {
                height: 250px;
            }

            .tracker-form-card,
            .tracker-history {
                padding: 20px;
            }

            th,
            td {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-heartbeat logo-icon"></i>
                    <h1>SehatinAja.com</h1>
                </div>
                <div class="user-info">
                    <span><i class="fas fa-user"></i> Halo, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></span>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <a href="logic/logout.php" class="btn btn-danger" onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="feature-detail">
            <h2>
                <?php
                $icons = [
                    'jadwal' => '📅',
                    'tracker' => '📊',
                    'nutrisi' => '🍎',
                    'ai' => '🤖'
                ];
                echo $icons[$fitur] . ' ' . $page_title;
                ?>
            </h2>
            <?php echo $content; ?>
        </section>
    </main>

    <script>
        // Tambahkan function ini di dalam script
        function confirmDelete(id) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                window.location.href = 'logic/delete_data.php?id=' + id + '&type=tracker&confirm=1';
            }
        }

        // Add smooth scrolling for chat
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>

</html>