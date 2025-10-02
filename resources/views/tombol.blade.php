<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tombol Spin Reward</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            }

            50% {
                box-shadow: 0 0 40px rgba(59, 130, 246, 0.8);
            }
        }

        @keyframes gradient-shift {
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

        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }

        .spin-button {
            transition: all 0.3s ease;
        }

        .spin-button:hover {
            transform: scale(1.1);
        }

        .spin-button:active {
            transform: scale(0.95);
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-connected {
            background-color: #10b981;
            animation: pulse 2s infinite;
        }

        .status-disconnected {
            background-color: #ef4444;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 min-h-screen flex flex-col justify-center">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4 drop-shadow-lg">
                <i class="fas fa-mobile-alt text-yellow-400 animate-pulse"></i>
                KONTROL SPIN REWARD
                <i class="fas fa-mobile-alt text-yellow-400 animate-pulse"></i>
            </h1>
            <div class="flex items-center justify-center space-x-2 text-white">
                <span class="status-indicator status-connected"></span>
                <span class="text-sm">Terhubung ke Monitor</span>
            </div>
        </div>

        <!-- Status Display -->
        <div class="max-w-md mx-auto mb-8">
            <div class="bg-white/95 backdrop-filter backdrop-blur-lg rounded-2xl p-6 shadow-2xl">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Status Monitor</h3>
                    <div id="statusDisplay" class="text-2xl font-bold text-green-600">
                        <i class="fas fa-check-circle"></i> Siap
                    </div>
                    <div id="lastUpdate" class="text-sm text-gray-500 mt-2">
                        Menunggu aksi...
                    </div>
                </div>
            </div>
        </div>

        <!-- Spin Button -->
        <div class="text-center mb-8">
            <button id="controlSpinBtn"
                class="spin-button bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-6 px-12 rounded-full shadow-2xl pulse-glow inline-flex items-center space-x-4 text-lg md:text-xl">
                <i class="fas fa-dice text-3xl"></i>
                <span>SPIN REWARD</span>
                <i class="fas fa-gift text-3xl"></i>
            </button>
        </div>

        <!-- Info Section -->
        <div class="max-w-md mx-auto">
            <div class="bg-white/90 backdrop-filter backdrop-blur-lg rounded-2xl p-4 shadow-xl">
                <div class="text-center text-gray-700">
                    <p class="text-sm">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Tekan tombol SPIN REWARD untuk memulai undian di monitor utama
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-backdrop fixed inset-0"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 mb-4">
                        <i class="fas fa-question text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Konfirmasi Spin Reward</h3>
                    <p class="text-gray-600 mb-6">
                        Apakah Anda yakin ingin memulai undian reward presensi?
                        <br>
                        <span class="text-sm text-gray-500">Monitor utama akan menampilkan animasi spin.</span>
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button id="cancelBtn"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-xl transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button id="confirmBtn"
                        class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                        <i class="fas fa-dice mr-2"></i>
                        Ya, Spin Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            // Show confirmation modal
            $("#controlSpinBtn").on("click", function(){
                $("#confirmModal").removeClass('hidden');
            });

            // Close modal on cancel
            $("#cancelBtn").on("click", function(){
                $("#confirmModal").addClass('hidden');
            });

            // Close modal on backdrop click
            $(".modal-backdrop").on("click", function(){
                $("#confirmModal").addClass('hidden');
            });

            // Proceed with spin on confirm
            $("#confirmBtn").on("click", function(){
                // Close modal
                $("#confirmModal").addClass('hidden');
                
                // Disable spin button during spinning
                $("#controlSpinBtn").prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                
                // Update status
                $("#statusDisplay").html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
                $("#lastUpdate").text('Memulai spin reward...');
                
                // Trigger spin on the main reward page
                $.get("{{ route('reward.spin.data') }}", function(data){
                    // Update status to success
                    $("#statusDisplay").html('<i class="fas fa-check-circle"></i> Berhasil!');
                    $("#lastUpdate").text('Spin reward selesai - ' + new Date().toLocaleTimeString());
                    
                    // Re-enable button after delay
                    setTimeout(() => {
                        $("#controlSpinBtn").prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                        $("#statusDisplay").html('<i class="fas fa-check-circle"></i> Siap');
                        $("#lastUpdate").text('Menunggu aksi...');
                    }, 2000);
                    
                }).fail(function() {
                    // Handle error
                    $("#statusDisplay").html('<i class="fas fa-exclamation-circle"></i> Error!');
                    $("#lastUpdate").text('Terjadi kesalahan - ' + new Date().toLocaleTimeString());
                    
                    // Re-enable button after delay
                    setTimeout(() => {
                        $("#controlSpinBtn").prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                        $("#statusDisplay").html('<i class="fas fa-check-circle"></i> Siap');
                        $("#lastUpdate").text('Menunggu aksi...');
                    }, 3000);
                });
            });
        });
    </script>
    {{-- <script>
        document.getElementById('spinBtn').addEventListener('click', () => {
            fetch('/spin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => console.log(data));
        });
    </script> --}}
</body>

</html>