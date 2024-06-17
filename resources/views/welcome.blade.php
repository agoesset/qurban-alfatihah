<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Qurban Alfatihah</title>
    <link rel="icon" href="public/storage/favicon.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /* Tailwind CSS code */
    </style>
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <img src="/storage/logo.png" alt="">
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    {{-- Progress Domba --}}
                    <div
                        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="badge badge-success">Progress Domba</div>

                            {{-- Penyembelihan --}}
                            @php
                                $dombaProgress = Helper::calculateProgress('countDomba', 'sembelihDomba');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Penyembelihan :
                                <span class="text-yellow-500">{{ $dombaProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $dombaProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $dombaProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($dombaProgress['persentase']) }}%</span>
                            </div>

                            {{-- Pengulitan --}}
                            @php
                                $dombaKulitProgress = Helper::calculateProgress('countDomba', 'kulitDomba');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Pengulitan :
                                <span class="text-yellow-500">{{ $dombaKulitProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $dombaKulitProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $dombaKulitProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($dombaKulitProgress['persentase']) }}%</span>
                            </div>

                            {{-- Penimbangan --}}
                            @php
                                $dombaTimbangProgress = Helper::calculateProgress('countDomba', 'timbangDomba');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Domba :
                                <span class="text-yellow-500">{{ $dombaTimbangProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $dombaTimbangProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $dombaTimbangProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($dombaTimbangProgress['persentase']) }}%</span>
                            </div>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Terakhir di update : {{ Helper::lastUpdatedPenimbanganDomba() }}
                            </p>

                        </div>
                    </div>

                    {{-- Progress Kambing --}}
                    <div
                        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="badge badge-success">Progress Kambing</div>

                            {{-- Penyembelihan --}}
                            @php
                                $kambingProgress = Helper::calculateProgress('countKambing', 'sembelihKambing');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Kambing :
                                <span class="text-yellow-500">{{ $kambingProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $kambingProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $kambingProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($kambingProgress['persentase']) }}%</span>
                            </div>

                            {{-- Pengulitan --}}
                            @php
                                $kambingKulitProgress = Helper::calculateProgress('countKambing', 'kulitKambing');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Kambing :
                                <span class="text-yellow-500">{{ $kambingKulitProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $kambingKulitProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $kambingKulitProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($kambingKulitProgress['persentase']) }}%</span>
                            </div>

                            {{-- Penimbangan --}}
                            @php
                                $kambingTimbangProgress = Helper::calculateProgress('countKambing', 'timbangKambing');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Penimbangan :
                                <span class="text-yellow-500">{{ $kambingTimbangProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $kambingTimbangProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $kambingTimbangProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($kambingTimbangProgress['persentase']) }}%</span>
                            </div>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Terakhir di update : {{ Helper::lastUpdatedPenimbanganKambing() }}
                            </p>


                        </div>
                    </div>

                    {{-- Progress Sapi --}}
                    <div
                        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="badge badge-success">Progress Sapi</div>

                            {{-- Penyembelihan --}}
                            @php
                                $sapiProgress = Helper::calculateProgress('countSapi', 'sembelihSapi');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Penyembelihan :
                                <span class="text-yellow-500">{{ $sapiProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $sapiProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $sapiProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($sapiProgress['persentase']) }}%</span>
                            </div>

                            {{-- Pengulitan --}}
                            @php
                                $sapiKulitProgress = Helper::calculateProgress('countSapi', 'kulitSapi');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Pengulitan :
                                <span class="text-yellow-500">{{ $sapiKulitProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $sapiKulitProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $sapiKulitProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($sapiKulitProgress['persentase']) }}%</span>
                            </div>

                            {{-- Penimbangan --}}
                            @php
                                $sapiTimbangProgress = Helper::calculateProgress('countSapi', 'timbangSapi');
                            @endphp
                            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Penimbangan :
                                <span class="text-yellow-500">{{ $sapiTimbangProgress['progres'] }}</span>
                                dari
                                <span class="text-green-500">{{ $sapiTimbangProgress['total'] }}</span>
                            </h2>

                            <div class="flex items-center mt-2">
                                <progress class="progress progress-success w-56 mr-2"
                                    value="{{ $sapiTimbangProgress['persentase'] }}" max="100"></progress>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ round($sapiTimbangProgress['persentase']) }}%</span>
                            </div>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Terakhir di update : {{ Helper::lastUpdatedPenimbanganSapi() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                        {{-- Pembungkusan --}}
                        <div
                            class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="badge badge-success">Pembungkusan</div>

                                {{-- Daging --}}
                                @php
                                    $dagingProgress = Helper::calculateProgress('countDaging', 'bungkusDaging');
                                @endphp
                                <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Daging :
                                    <span class="text-yellow-500">{{ $dagingProgress['progres'] }}</span>
                                    dari
                                    <span class="text-green-500">{{ $dagingProgress['total'] }}</span>
                                </h2>

                                <div class="flex items-center mt-2">
                                    <progress class="progress progress-success w-56 mr-2"
                                        value="{{ $dagingProgress['persentase'] }}" max="100"></progress>
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ round($dagingProgress['persentase']) }}%</span>
                                </div>

                                {{-- Jeroan --}}
                                @php
                                    $jeroanProgress = Helper::calculateProgress('countJeroan', 'bungkusJeroan');
                                @endphp
                                <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Jeroan :
                                    <span class="text-yellow-500">{{ $jeroanProgress['progres'] }}</span>
                                    dari
                                    <span class="text-green-500">{{ $jeroanProgress['total'] }}</span>
                                </h2>

                                <div class="flex items-center mt-2">
                                    <progress class="progress progress-success w-56 mr-2"
                                        value="{{ $jeroanProgress['persentase'] }}" max="100"></progress>
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ round($jeroanProgress['persentase']) }}%</span>
                                </div>

                                {{-- Kepala & Kaki --}}
                                @php
                                    $kepalaKakiProgress = Helper::calculateProgress(
                                        'countKepalaKaki',
                                        'bungkusKepalaKaki',
                                    );
                                @endphp
                                <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Kepala & Kaki :
                                    <span class="text-yellow-500">{{ $kepalaKakiProgress['progres'] }}</span>
                                    dari
                                    <span class="text-green-500">{{ $kepalaKakiProgress['total'] }}</span>
                                </h2>

                                <div class="flex items-center mt-2">
                                    <progress class="progress progress-success w-56 mr-2"
                                        value="{{ $kepalaKakiProgress['persentase'] }}" max="100"></progress>
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ round($kepalaKakiProgress['persentase']) }}%</span>
                                </div>
                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Terakhir di update : {{ Helper::lastUpdatedPembungkusan() }}
                                </p>
                            </div>
                        </div>

                        {{-- Distribusi Qurban --}}
                        <div
                            class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="badge badge-success">Distribusi Qurban</div>

                                {{-- Shohibul Qurban --}}
                                @php
                                    $shohibulQurbanProgress = Helper::calculateProgress(
                                        'countShohibulQurban',
                                        'distribusiShohibulQurban',
                                    );
                                @endphp
                                <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Shohibul Qurban :
                                    <span class="text-yellow-500">{{ $shohibulQurbanProgress['progres'] }}</span>
                                    dari
                                    <span class="text-green-500">{{ $shohibulQurbanProgress['total'] }}</span>
                                </h2>

                                <div class="flex items-center mt-2">
                                    <progress class="progress progress-success w-56 mr-2"
                                        value="{{ $shohibulQurbanProgress['persentase'] }}"
                                        max="100"></progress>
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ round($shohibulQurbanProgress['persentase']) }}%</span>
                                </div>

                                {{-- Penerima Manfaat --}}
                                @php
                                    $penerimaManfaatProgress = Helper::calculateProgress(
                                        'countPenerimaManfaat',
                                        'distribusiPenerimaManfaat',
                                    );
                                @endphp
                                <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Penerima Manfaat :
                                    <span class="text-yellow-500">{{ $penerimaManfaatProgress['progres'] }}</span>
                                    dari
                                    <span class="text-green-500">{{ $penerimaManfaatProgress['total'] }}</span>
                                </h2>

                                <div class="flex items-center mt-2">
                                    <progress class="progress progress-success w-56 mr-2"
                                        value="{{ $penerimaManfaatProgress['persentase'] }}"
                                        max="100"></progress>
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400">{{ round($penerimaManfaatProgress['persentase']) }}%</span>
                                </div>
                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Terakhir di update : {{ Helper::lastUpdatedDistribusiQurban() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>
