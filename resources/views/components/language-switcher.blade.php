<div class="dropdown">
    <button class="btn btn-sm rounded-pill lang-switcher-btn" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-translate me-1"></i> 
        <span class="d-none d-md-inline">{{ app()->getLocale() }}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 py-2" aria-labelledby="languageDropdown">
        @php
            $activeLanguages = \App\Models\Language::where('is_active', true)->get();
        @endphp
        
        @foreach($activeLanguages as $language)
            <li>
                <a class="dropdown-item px-3 py-2 rounded-3 mx-1 d-flex align-items-center {{ app()->getLocale() == $language->code ? 'active' : '' }}" 
                   href="{{ route('language.switch', $language->code) }}">
                    <span class="flag-icon me-2">
                        @if($language->code == 'ar')
                            🇸🇦
                        @elseif($language->code == 'en')
                            🇬🇧
                        @elseif($language->code == 'fr')
                            🇫🇷
                        @elseif($language->code == 'es')
                            🇪🇸
                        @elseif($language->code == 'de')
                            🇩🇪
                        @elseif($language->code == 'it')
                            🇮🇹
                        @elseif($language->code == 'ru')
                            🇷🇺
                        @elseif($language->code == 'zh')
                            🇨🇳
                        @elseif($language->code == 'ja')
                            🇯🇵
                        @elseif($language->code == 'ko')
                            🇰🇷
                        @elseif($language->code == 'tr')
                            🇹🇷
                        @elseif($language->code == 'pt')
                            🇵🇹
                        @elseif($language->code == 'id')
                            🇮🇩
                        @elseif($language->code == 'ms')
                            🇲🇾
                        @elseif($language->code == 'fa')
                            🇮🇷
                        @elseif($language->code == 'ur')
                            🇵🇰
                        @elseif($language->code == 'sw')
                            🇰🇪
                        @elseif($language->code == 'bn')
                            🇧🇩
                        @elseif($language->code == 'ha')
                            🇳🇬
                        @elseif($language->code == 'so')
                            🇸🇴
                        @elseif($language->code == 'tl')
                            🇵🇭
                        @else
                            🌐
                        @endif
                    </span>
                    {{ $language->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div> 