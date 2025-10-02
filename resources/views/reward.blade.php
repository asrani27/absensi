<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reward Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

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

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }

        .white-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .spinner {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
            font-size: 1.5rem;
            color: #666;
        }

        .reward-card {
            transition: all 0.3s ease;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .reward-card:hover {
            transform: translateY(-10px) scale(1.02);
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
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 min-h-screen flex flex-col justify-center">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h2 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
                <i class="fas fa-star text-yellow-400 animate-pulse"></i>
                REWARD PRESENSI
                <i class="fas fa-star text-yellow-400 animate-pulse"></i>
            </h2>

        </div>

        <!-- Reward Boxes - Vertical Layout -->
        <div class="w-full max-w-6xl mx-auto mb-12" id="reward-boxes">
            <div class="space-y-6">
                <!-- Box 1 -->
                <div class="reward-card">
                    <div class="white-card rounded-2xl p-8 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full -mr-16 -mt-16">
                        </div>
                        <div class="relative z-10 flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <img src="/images/reward/number-1.png" alt="1" class="w-20 h-20 float-animation">
                            </div>
                            <div class="flex-1">
                                <h3 class="box-name text-3xl font-bold text-gray-800 mb-2" id="winner-1">Nama 1</h3>
                                <p class="box-instansi text-lg text-gray-600" id="instansi-1">Instansi 1</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Box 2 -->
                <div class="reward-card">
                    <div class="white-card rounded-2xl p-8 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100 to-blue-100 rounded-full -mr-16 -mt-16">
                        </div>
                        <div class="relative z-10 flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <img src="/images/reward/number-2.png" alt="2" class="w-20 h-20 float-animation"
                                    style="animation-delay: 0.5s;">
                            </div>
                            <div class="flex-1">
                                <h3 class="box-name text-3xl font-bold text-gray-800 mb-2" id="winner-2">Nama 2</h3>
                                <p class="box-instansi text-lg text-gray-600" id="instansi-2">Instansi 2</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Box 3 -->
                <div class="reward-card">
                    <div class="white-card rounded-2xl p-8 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100 to-red-100 rounded-full -mr-16 -mt-16">
                        </div>
                        <div class="relative z-10 flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <img src="/images/reward/number-3.png" alt="3" class="w-20 h-20 float-animation"
                                    style="animation-delay: 1s;">
                            </div>
                            <div class="flex-1">
                                <h3 class="box-name text-3xl font-bold text-gray-800 mb-2" id="winner-3">Nama 3</h3>
                                <p class="box-instansi text-lg text-gray-600" id="instansi-3">Instansi 3</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spin Button -->
        {{-- <div class="text-center">
            <button id="spinBtn"
                class="spin-button bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-4 px-8 rounded-full shadow-2xl pulse-glow inline-flex items-center space-x-3">
                <i class="fas fa-dice text-2xl"></i>
                <span class="text-xl">SPIN REWARD</span>
                <i class="fas fa-gift text-2xl"></i>
            </button>


        </div> --}}
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
                        <span class="text-sm text-gray-500">Tindakan ini akan memilih 3 pemenang secara acak.</span>
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
        let isSpinning = false;
        let lastSpinTime = 0;
        
        $(function(){
            
            // Show confirmation modal
            $("#spinBtn").on("click", function(){
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
                
                // Get data and start animation
                $.get("{{ route('reward.spin.data') }}", function(data){
                    startSpinAnimation(data);
                }).fail(function() {
                    // Handle error
                    alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
                    $("#spinBtn").prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    $(".box-name").text('Error');
                    $(".box-instansi").text('Silakan coba lagi');
                });
            });
        });
        
        function startSpinAnimation(data) {
            if (isSpinning) return;
            
            isSpinning = true;
            
            // Disable spin button during spinning
            $("#spinBtn").prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
            
            // Show spinner in all boxes
            $(".box-name").html('<div class="spinner"><i class="fas fa-spinner fa-spin text-gray-600"></i></div>');
            $(".box-instansi").html("");

            // Always fill 3 boxes
            for (let i = 0; i < 3; i++) {
                setTimeout(() => {
                    let card = $(".reward-card").eq(i);
                    
                    // Add animation effect
                    card.addClass('animate-pulse');
                    
                    if (data[i]) {
                        // If there is employee data
                        card.find(".box-name").text(data[i].nama);
                        card.find(".box-instansi").text(data[i].skpd);
                    } else {
                        // If no data → dash
                        card.find(".box-name").text("-");
                        card.find(".box-instansi").text("-");
                    }
                    
                    // Remove animation after a moment
                    setTimeout(() => {
                        card.removeClass('animate-pulse');
                    }, 500);
                    
                    // Re-enable button on last box
                    if (i === 2) {
                        setTimeout(() => {
                            $("#spinBtn").prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                            isSpinning = false;
                        }, 1000);
                    }
                }, (i+1) * 1000); // Progressive delay 1s, 2s, 3s
            }
        }
    </script>


    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        Echo.channel('reward')
            .listen('SpinEvent', (data) => {
        console.log("Event diterima:", data);

        let winners = data.winners;

        // Place each winner in their respective card
        for (let i = 0; i < 3; i++) {
            let winnerElement = document.getElementById(`winner-${i + 1}`);
            let instansiElement = document.getElementById(`instansi-${i + 1}`);
            
            if (winners[i]) {
                // If there is winner data
                winnerElement.innerText = winners[i].nama;
                instansiElement.innerText = winners[i].skpd || winners[i].instansi || '-';
            } else {
                // If no data → dash
                winnerElement.innerText = '-';
                instansiElement.innerText = '-';
            }
        }
    });

    </script>
</body>

</html>