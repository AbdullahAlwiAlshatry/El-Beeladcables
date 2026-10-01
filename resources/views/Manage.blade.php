@extends('layouts.app', ['titlePage' => 'إدارة المنتجات'])

@section('myapp')
    <link rel="stylesheet" href="{{ asset('assets/css/manage-cards.css') }}">

    <div class="cards-grid">
        @forelse ($products as $product)
            <article class="product-card" data-product-type="{{ $product->ProductType }}"
                data-product-name="{{ $product->ProductName }}" data-product-title="{{ $product->ProductTitle }}">
                <div class="product-card-inner">
                    <div class="product-image-wrap"> <img src="{{ asset('assets/uploads/' . $product->ProductPicture) }}"
                            alt="{{ $product->ProductName }}">
                    </div>
                    <div class="product-body">
                        <h3 class="product-name">
                            {{ $product->ProductName }} </h3>
                        @if (!empty($product->ProductTitle))
                            <p class="product-description"> {{ $product->ProductTitle }} </p>
                        @endif

                        @if (!empty($product->tags) && count($product->tags) > 0)
                            <button class="product-info-btn" data-tags='@json($product->tags)'>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="16" x2="12" y2="12" />
                                    <line x1="12" y1="8" x2="12.01" y2="8" />
                                </svg>
                                <span>المواصفات</span>
                                <span class="info-count">{{ count($product->tags) }}</span>
                            </button>
                        @endif

                        <div class="manage-product-actions">
                            <a href="{{ route('manage.edit', $product->ProductID) }}"
                                class="manage-action manage-action-edit">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    <path
                                        d="M19.5 7.125 16.862 4.487M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span>تعديل</span>
                            </a>

                            <form action="{{ route('manage.destroy', $product->ProductID) }}" method="POST"
                                class="manage-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="manage-action manage-action-delete">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />
                                    </svg>
                                    <span>حذف</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="manage-empty-state">
                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2h12v20H6z" />
                    <path d="M9 6h6M9 10h6M9 14h4" />
                </svg>
                <h2>لا توجد منتجات حالياً</h2>
                <p>ستظهر المنتجات هنا عند إضافتها إلى المتجر.</p>
            </div>
        @endforelse

    </div>

    {{-- Tags popup --}}
    <div class="tags-popup-overlay" id="tagsPopupOverlay"></div>
    <div class="tags-popup" id="tagsPopup">
        <button class="tags-popup-close" id="tagsPopupClose">&times;</button>
        <h3 class="tags-popup-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
                <line x1="7" y1="7" x2="7.01" y2="7" />
            </svg>
            <span>المواصفات والكلمات المفتاحية</span>
        </h3>
        <div class="tags-popup-list" id="tagsPopupList"></div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var cards = document.querySelectorAll('.product-card');
                cards.forEach(function(card, index) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    setTimeout(function() {
                        card.style.transition =
                            'opacity 0.5s ease, transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                var tagsPopup = document.getElementById('tagsPopup');
                var tagsPopupOverlay = document.getElementById('tagsPopupOverlay');
                var tagsPopupClose = document.getElementById('tagsPopupClose');
                var tagsPopupList = document.getElementById('tagsPopupList');

                function openTagsPopup(tags) {
                    if (!tagsPopup || !tagsPopupList) return;
                    tagsPopupList.innerHTML = '';
                    tags.forEach(function(tag) {
                        var el = document.createElement('span');
                        el.className = 'tag-item';
                        el.textContent = tag.Tag || tag.tag || tag;
                        tagsPopupList.appendChild(el);
                    });
                    tagsPopup.classList.add('active');
                    if (tagsPopupOverlay) tagsPopupOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }

                function closeTagsPopup() {
                    if (tagsPopup) tagsPopup.classList.remove('active');
                    if (tagsPopupOverlay) tagsPopupOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }

                var infoBtns = document.querySelectorAll('.product-info-btn');
                infoBtns.forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        try {
                            var tags = JSON.parse(btn.getAttribute('data-tags')) || [];
                            openTagsPopup(tags);
                        } catch (err) {
                            openTagsPopup([]);
                        }
                    });
                });

                if (tagsPopupClose) tagsPopupClose.addEventListener('click', closeTagsPopup);
                if (tagsPopupOverlay) tagsPopupOverlay.addEventListener('click', closeTagsPopup);

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeTagsPopup();
                });
            });
        </script>
    @endpush
@endsection
