<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Skinscan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Header -->
    <x-app-header />


    <main>
        <div class="container">
            <section class="skin-scan-section">
                <div class="skin-scan-container">
                    <h1 class="main-title">ระบบสแกนผิวหน้า</h1>
                    <h2 class="sub-title">
                    เริ่มต้นดูแลผิวของคุณอย่างถูกวิธี ด้วยการวิเคราะห์ใบหน้าด้วยระบบ AI
                    </h2>
                    <p class="description">
                    เพียงแค่ถ่ายภาพหรืออัปโหลดรูปใบหน้า ระบบจะดำเนินการวิเคราะห์ทันที
                    เพื่อประเมินประเภทของสิว บริเวณที่พบปัญหา และปัจจัยที่อาจเป็นสาเหตุของการเกิดสิว
                    โดยใช้เทคโนโลยี AI ที่พัฒนาขึ้นเฉพาะสำหรับการตรวจจับและประมวลผลปัญหาผิวอย่างแม่นยำ
                    </p>

                    <div class="divider"></div>

                    <p class="privacy-text">
                    ระบบใช้งานง่าย ปลอดภัย และ <span class="highlight">ไม่บันทึกรูปภาพของคุณ</span>
                    เพื่อความเป็นส่วนตัวสูงสุด <br>
                    พร้อมหรือยัง? ให้ AI ช่วยดูแลผิวคุณในทุกวัน
                    </p>

                    <div class="logo-container">
                        <div class="logo-icon">
                            <img src="{{URL::asset('image/Acne_W.png')}}" alt="AcneScan Logo" class="footer-logo-img">
                        </div>
                        <span class="logo-text">SkinScan</span>
                    </div>
                </div>
            </section>

            <div class="scan-container">
                <!-- Mode Selection -->
                <div id="modeSelection" class="scan-step active">

                    @guest
                        <div style="text-align: center; padding: 2rem;">
                            <p style="font-size: 1.1rem; color: var(--gray-700); margin-bottom: 1.5rem;">กรุณาเข้าสู่ระบบเพื่อใช้งานฟีเจอร์นี้</p>
                            <a href="{{ route('login', ['intended' => url()->current()]) }}" class="btn-primary" style="font-size: 1.125rem; padding: 0.875rem 2rem;">
                                <i class="fa-solid fa-user"></i>
                                <span>เข้าสู่ระบบ / สมัครสมาชิก</span>
                            </a>
                        </div>
                    @endguest

                    @auth
                        <h2>เริ่มการวิเคราะห์ผิวหน้าของคุณ</h2>
                        <div class="scan-methods">
                            {{-- <button class="scan-method" onclick="selectScanMode('camera')">
                                <i class="fas fa-camera"></i>
                                <h3>สแกนผ่านกล้อง</h3>
                                <p>ถ่ายภาพสดโดยใช้กล้องของอุปกรณ์ของคุณเพื่อวิเคราะห์ทันที</p>
                            </button> --}}

                            <button class="scan-method" onclick="selectScanMode('upload')">
                                <i class="fas fa-upload"></i>
                                <h3>อัปโหลดรูปภาพ</h3>
                                <p>อัปโหลดรูปถ่ายที่มีอยู่จากอุปกรณ์ของคุณเพื่อวิเคราะห์</p>
                            </button>
                    @endauth
                    </div>

                    <div class="tip">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <i class="tip-info-icon">!</i>
                            <h2 style="margin: 0;">เพื่อผลลัพธ์ที่ดีที่สุด</h2>
                        </span>

                        <div class="tip-list-wrapper">
                            <div class="tip-bullet">
                                <ul>
                                    <li>ให้มีแสงสว่างเพียงพอบนใบหน้า</li>
                                    <li>ลบเครื่องสำอางและทำความสะอาดใบหน้า</li>
                                </ul>
                                <ul>
                                    <li>หันหน้าเข้ากล้องโดยตรง</li>
                                    <li>ใช้ภาพความละเอียดสูง</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Mode -->
                {{-- <div id="cameraMode" class="scan-step">
                    <div class="scan-header">
                        <h2>Camera Scanner</h2>
                        <button class="btn-cancel" onclick="resetScan()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>

                    <div class="camera-container">
                        <video id="video" autoplay playsinline muted></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div class="camera-overlay">
                            <div class="face-guide">
                                <span>Position your face here</span>
                            </div>
                        </div>
                    </div>

                    <div class="camera-controls">
                        <button class="btn-primary" onclick="capturePhoto()">
                            <i class="fas fa-camera"></i>
                            <span>Capture Photo</span>
                        </button>
                    </div>
                </div> --}}

                <!-- Upload Mode -->
                <div id="uploadMode" class="scan-step">
                    <div class="scan-header">
                        <h2>Upload Photo</h2>
                        <button class="btn-cancel" onclick="resetScan()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>

                    <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-upload"></i>
                        <h3>Click to upload or drag & drop</h3>
                        <p>JPG, PNG, or WEBP (max 10MB)</p>
                    </div>

                    <input type="file" id="fileInput" accept="image/*" style="display: none;" onchange="handleFileUpload(event)">
                </div>

                <!-- Scanning State -->
                <div id="scanningState" class="scan-step">
                    <div class="scanning-content">
                        <div class="scan-image-container">
                            <img id="scanImage" alt="Uploaded face">
                        </div>

                        <div class="scanning-status">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Analyzing your skin...</span>
                        </div>

                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>

                        <p>Our AI is analyzing your skin condition. This may take a few seconds.</p>
                    </div>
                </div>

                <!-- Results -->
                <div id="resultsState" class="scan-step">
                    <div class="results-header">
                        <h2><i class="fas fa-check-circle"></i> Analysis Complete</h2>
                        <button class="btn-secondary" onclick="resetScan()">
                            <i class="fas fa-redo"></i> New Scan
                        </button>
                    </div>

                    <div class="results-content">
                        <div class="results-image">
                            <img id="resultImage" alt="Analyzed face">
                        </div>
                            <!-- แก้ตรงนี้จ้าาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาา -->
                        <div class="results-details">
                            <div class="recommendations">
                                <h3><i class="fas fa-bolt"></i> Recommended Actions</h3>
                                <ul class="recommendations-list">
                                </ul>
                            </div>

                            <h2 class="section-title">สกินแคร์แนะนำ</h2>
                            <div id="acneTypeCardsContainer"></div>

                            <!-- Modal Template -->
                            <div id="productModal" class="modal">
                                <div class="modal-content">
                                    <span class="close-button" onclick="closeModal()">&times;</span>
                                    <h3 id="modalTitle"></h3>
                                    <div id="modalProducts" class="product-grid"></div>
                                </div>
                            </div>

                            <div class="skincare-grid" id="skincareCards"></div>
                            <div class="disclaimer">
                                <p><strong>Disclaimer:</strong> ผลิตภัณฑ์ดูแลผิวที่ระบบแนะนำ เป็นไปตามข้อมูลการวิเคราะห์เบื้องต้นและไม่ได้ถือเป็นคำวินิจฉัยทางการแพทย์ หากพบอาการระคายเคือง แพ้ หรือผิดปกติใด ๆ หลังการใช้ผลิตภัณฑ์ ควรหยุดใช้ทันที และปรึกษาแพทย์หรือผู้เชี่ยวชาญด้านผิวหนัง เพื่อความปลอดภัยและผลลัพธ์ที่เหมาะสมที่สุดต่อสภาพผิวของคุณ.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="logo-icon">
                            <img src="{{URL::asset('image/Acne_W.png')}}" alt="AcneScan Logo" class="footer-logo-img">
                        </div>
                        <span class="logo-text">AcneScan</span>
                    </div>
                    <p>การวิเคราะห์สิวด้วย AI ขั้นสูง พร้อมคำแนะนำการดูแลผิวเฉพาะบุคคล ดูแลสุขภาพผิวของคุณด้วยเทคโนโลยีการสแกนระดับมืออาชีพ</p>
                </div>

                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/Skinscan/home">Home</a></li>
                        <li><a href="/Skinscan/anceinfomation">Acne Info</a></li>
                        <li><a href="/Skinscan/facescan">Face Scan</a></li>
                        <li><a href="/Skinscan/aboutus">About Us</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h3>Contact</h3>
                    <div class="contact-info">
                        <p>Bachelor of Science Program in Artificial Intelligence</p>
                        <p>มหาวิทยาลัยขอนแก่น 123 หมู่ 16 <br>ถนนมิตรภาพ ตำบลในเมือง อำเภอเมืองขอนแก่น 40002</p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 SkinScan. All rights reserved.</p>
            </div>
        </div>
    </footer>


</body>
</html>
