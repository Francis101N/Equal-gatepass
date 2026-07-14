<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equal Logistics Ltd - Gate Pass System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .mono {
            font-family: 'JetBrains Mono', sans-serif;
        }

        /* Brand Colors */
        .brand-bg-crimson {
            background-color: #dc1d3a;
        }

        .brand-text-crimson {
            color: #dc1d3a;
        }

        .brand-border-crimson {
            border-color: #dc1d3a;
        }

        .brand-focus-ring:focus {
            border-color: #dc1d3a;
            box-shadow: 0 0 0 2px rgba(220, 29, 58, 0.15);
        }

        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }

            .no-print {
                display: none !important;
            }

            .print-card {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%) scale(1.1);
                box-shadow: none !important;
                border: 2px solid #000000 !important;
                background: #ffffff !important;
            }

            .print-bg-crimson {
                background-color: #dc1d3a !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-text-white {
                color: #ffffff !important;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased selection:bg-[#dc1d3a] selection:text-white">


    <header class="no-print border-b border-slate-200 bg-white sticky top-0 z-50 px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">

            <div class="h-16 w-auto flex items-center justify-center overflow-hidden rounded-lg">
                <img src="images/Equaloffshorelimited.Logoalone-ezgif.com-crop.gif" alt="Equal Logistics Logo" class="h-full object-contain max-w-[180px]">
            </div>

            <div class="h-6 w-[1px] bg-slate-200 mx-1"></div>
            <div>
                <h1 class="text-sm font-bold tracking-wider text-slate-900 uppercase">Equal Logistics Limited</h1>
                <p class="text-[10px] tracking-wide text-slate-500 uppercase font-medium">Internal Security System</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs font-mono text-slate-600 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg">
            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
            <span>SYSTEM ACTIVE</span>
        </div>
    </header>


    <main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <section class="no-print lg:col-span-6 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col gap-5">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Gate Pass Application</h2>
                <p class="text-xs text-slate-500 mt-0.5">Submit the form below. Your request will be instantly routed to HR for digital review.</p>
            </div>

            <!-- System Message Containers Hooked to PHP Processing Status States -->
            <?php if (!empty($success_message)): ?>
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-xs font-bold text-emerald-900 uppercase tracking-wide">Submission Successful</h4>
                        <p class="text-xs text-emerald-700 mt-0.5"><?php echo htmlspecialchars($success_message); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#dc1d3a] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-xs font-bold text-red-900 uppercase tracking-wide">Submission Failed</h4>
                        <p class="text-xs text-red-700 mt-0.5"><?php echo htmlspecialchars($error_message); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form id="gate-pass-form" class="grid grid-cols-1 sm:grid-cols-2 gap-4" method="post" action="apply.php" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to submit this application for HR approval? Please verify that all information entered is correct.');">

                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Passport Photo</label>
                    <input type="file" id="input-photo" name="passport_photo" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-600 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[#dc1d3a] file:text-white hover:file:bg-[#b5142b] file:cursor-pointer focus:outline-none transition">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Name of Staff</label>
                    <input type="text" id="input-name" required value="" name="staff_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none brand-focus-ring transition">
                </div>

                <!-- New Branch Dropdown -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
                    <select id="input-branch" required name="branch" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none brand-focus-ring transition cursor-pointer">
                        <option value="" disabled selected>Select a branch...</option>
                        <option value="Omisore">Omisore</option>
                        <option value="Fingesi">Fingesi</option>
                        <option value="Apapa">Apapa</option>
                        <!-- <option value="Port Harcourt">Port Harcourt</option> -->
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Department</label>
                    <input type="text" id="input-dept" required value="" name="department" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none brand-focus-ring transition">
                </div>

                <!-- Added Email Input Field -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" id="input-email" required value="" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none brand-focus-ring transition" placeholder="staff@company.com">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Date</label>
                    <input type="date" id="input-date" required name="pass_date"
                        value=""
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none brand-focus-ring transition">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Destination</label>
                    <input type="text" id="input-dest" required value="" name="destination" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none brand-focus-ring transition">
                </div>

                <!-- Expanded to sm:col-span-2 to keep the grid perfectly balanced -->
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Purpose of Exit</label>
                    <input type="text" id="input-purpose" required value="" name="purpose_of_exit" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none brand-focus-ring transition">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Staff Signature Initials</label>
                    <input type="text" id="input-sig" required value="" name="signature_initials" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 font-mono focus:outline-none brand-focus-ring transition">
                </div>

                <div class="sm:col-span-2 pt-2">
                    <button type="submit" class="w-full bg-[#dc1d3a] hover:bg-[#b5142b] text-white font-extrabold text-sm py-3.5 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2 tracking-wide uppercase">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Submit for HR Approval
                    </button>
                </div>

            </form>


        </section>

        <!-- Right Side Badging Preview Panel Block -->
        <section class="lg:col-span-6 flex justify-center items-center p-2">
            <div id="gate-pass-badge" class="print-card w-full max-w-md bg-white border border-slate-200 rounded-[32px] overflow-hidden shadow-xl relative">

                <div class="print-bg-crimson bg-[#dc1d3a] p-6 flex justify-between items-center relative">
                    <div class="absolute inset-0 opacity-5 bg-[linear-gradient(45deg,#fff_25%,transparent_25%,transparent_50%,#fff_50%,#fff_75%,transparent_75%,transparent)] [background-size:24px_24px]"></div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 bg-white rounded-sm transform rotate-45"></span>
                            <h3 class="text-sm font-black tracking-[0.2em] text-white uppercase print-text-white">EQUAL LOGISTICS</h3>
                        </div>
                        <p class="text-[9px] font-mono tracking-widest text-red-100 uppercase mt-0.5 opacity-80">Official Movement Authorization</p>
                    </div>

                    <span id="badge-status-pill" class="text-[9px] font-black tracking-wider px-2.5 py-1 rounded-md bg-white text-[#dc1d3a] border border-white/20 uppercase shadow-sm">
                        PENDING APPROVAL
                    </span>
                </div>

                <div class="p-6 flex flex-col gap-6">

                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div id="photo-frame" class="w-16 h-16 bg-red-50 rounded-xl border border-red-100 flex items-center justify-center text-slate-400 shrink-0 overflow-hidden relative">
                            <img id="badge-photo" src="" alt="Passport Profile" class="w-full h-full object-cover hidden">
                            <svg id="badge-avatar-placeholder" class="w-8 h-8 text-[#dc1d3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="overflow-hidden w-full">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Authorized Carrier</span>
                            <h4 id="badge-name" class="text-xl font-bold text-slate-900 tracking-tight truncate"></h4>
                            <!-- Added live view email display node -->
                            <p id="badge-email" class="text-[11px] text-slate-500 font-mono truncate -mt-0.5"></p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span id="badge-branch-tag" class="text-xs text-slate-800 font-extrabold truncate"></span>
                                <span class="text-slate-300 text-xs font-bold">•</span>
                                <p id="badge-dept" class="text-xs text-[#dc1d3a] font-semibold truncate"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic 3-Column Meta Data Layout Grid -->
                    <div class="grid grid-cols-3 gap-x-3 gap-y-4 text-xs border-b border-slate-100 pb-5">
                        <div class="space-y-0.5 col-span-1">
                            <span class="text-slate-400 font-bold tracking-wide text-[10px] uppercase block">Assigned Branch</span>
                            <span id="badge-branch" class="text-slate-800 font-extrabold text-sm block truncate"></span>
                        </div>
                        <div class="space-y-0.5 col-span-1">
                            <span class="text-slate-400 font-bold tracking-wide text-[10px] uppercase block">Destination Point</span>
                            <span id="badge-dest" class="text-slate-800 font-extrabold text-sm block truncate"></span>
                        </div>
                        <div class="space-y-0.5 col-span-1">
                            <span class="text-slate-400 font-bold tracking-wide text-[10px] uppercase block">Mission / Purpose</span>
                            <span id="badge-purpose" class="text-slate-800 font-extrabold text-sm block truncate"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-100 font-mono text-center">
                        <div class="border-r border-slate-200">
                            <span class="text-slate-400 text-[9px] font-bold uppercase tracking-wider block mb-0.5">Pass Date</span>
                            <span id="badge-date" class="text-slate-800 text-xs font-bold"></span>
                        </div>
                        <div class="border-r border-slate-200">
                            <span class="text-slate-400 text-[9px] font-bold uppercase tracking-wider block mb-0.5">Time Out</span>
                            <span id="badge-timeout" class="text-[#dc1d3a] text-xs font-bold">NIL</span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[9px] font-bold uppercase tracking-wider block mb-0.5">Expected In</span>
                            <span id="badge-timein" class="text-slate-800 text-xs font-bold">NIL</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 pt-1">
                        <div class="space-y-1">
                            <span class="text-slate-400 font-bold tracking-wide text-[10px] uppercase block">Staff Signature Affirmation</span>
                            <div class="h-14 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between px-4">
                                <span id="badge-sig" class="font-serif text-slate-700 text-lg italic tracking-widest select-none font-medium"></span>
                                <div class="text-right">
                                    <span class="text-[8px] font-mono block text-slate-400 uppercase">Verification ID</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-500">PENDING_DB</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex items-center justify-between text-[10px] text-slate-400 font-mono">
                    <span>SECURITY REF: EQL-SYSTEM-REQ</span>
                    <span class="tracking-widest font-bold text-slate-800">||| | |||| | ||</span>
                </div>
            </div>
        </section>
    </main>


    <footer class="no-print mt-auto border-t border-slate-200 bg-white shadow-[0_-1px_3px_rgba(0,0,0,0.02)] transition-all">
        <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col items-center justify-center gap-4">


            <div class="flex items-center justify-center gap-2 text-xs text-slate-500 text-center">
                <span class="font-bold tracking-wider text-slate-900 uppercase">Equal Logistics Ltd</span>
                <span class="text-slate-300">|</span>
                <p>&copy; 2026 Gate Pass System. All Rights Reserved.</p>
            </div>

        </div>
    </footer>

    <script>
        const inputs = ['name', 'email', 'dept', 'dest', 'purpose', 'date', 'sig'];

        inputs.forEach(id => {
            document.getElementById(`input-${id}`).addEventListener('input', (e) => {
                document.getElementById(`badge-${id}`).innerText = e.target.value;
            });
        });

        document.getElementById('input-branch').addEventListener('change', (e) => {
            const selectedBranch = e.target.value;
            document.getElementById('badge-branch').innerText = selectedBranch;
            document.getElementById('badge-branch-tag').innerText = selectedBranch;
        });

        document.getElementById('input-photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const badgePhoto = document.getElementById('badge-photo');
            const placeholder = document.getElementById('badge-avatar-placeholder');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    badgePhoto.src = event.target.result;
                    badgePhoto.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                badgePhoto.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });

        function handlePassSubmit(event) {
            event.preventDefault();

            const payload = {
                staffName: document.getElementById('input-name').value,
                email: document.getElementById('input-email').value, // Captured new email input data string
                branch: document.getElementById('input-branch').value,
                department: document.getElementById('input-dept').value,
                date: document.getElementById('input-date').value,
                destination: document.getElementById('input-dest').value,
                purpose: document.getElementById('input-purpose').value,
                // timeOut: document.getElementById('input-timeout').value,
                // timeIn: document.getElementById('input-timein').value,
                signatureInitials: document.getElementById('input-sig').value,
                passportDataUrl: document.getElementById('badge-photo').src || null,
                timestamp: new Date().toISOString()
            };

            console.log("Transmitting complete payload to corporate database endpoint...", payload);
            alert(`✅ Request submitted for ${payload.staffName}.\nAn authorization request notification email alongside the identity photo matrix has been successfully dispatched to HR.`);
        }
    </script>

</body>

</html>