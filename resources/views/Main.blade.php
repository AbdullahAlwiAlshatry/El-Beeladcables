@extends('layouts.app', ['titlePage' => 'الرئيسية'])
@section('myapp')
    <div class="cards-grid">
        @foreach ($products as $product)
            <article class="product-card" data-product-type="{{ $product->ProductType }}"
                data-product-name="{{ $product->ProductName }}" data-product-title="{{ $product->ProductTitle }}">
                <div class="product-card-inner">
                    {{-- صورة المنتج --}} <div class="product-image-wrap"> <img
                            src="{{ asset('assets/uploads/' . $product->ProductPicture) }}" alt="{{ $product->ProductName }}">
                    </div>
                    {{-- محتوى البطاقة --}} <div class="product-body"> {{-- اسم المنتج --}} <h3 class="product-name">
                            {{ $product->ProductName }} </h3> {{-- وصف / عنوان المنتج --}} @if (!empty($product->ProductTitle))
                            <p class="product-description"> {{ $product->ProductTitle }} </p>
                        @endif
                        {{-- الأزرار --}}
                        <div class="product-actions">
                            {{-- زر المواصفات --}} @if (!empty($product->tags) && count($product->tags) > 0)
                                <button type="button" class="product-info-btn" data-tags='@json($product->tags)'>
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="16" x2="12" y2="12" />
                                        <line x1="12" y1="8" x2="12.01" y2="8" />
                                    </svg> <span>المواصفات</span> <span class="info-count"> {{ count($product->tags) }}
                                    </span> </button>
                            @endif
                            {{-- زر إضافة إلى السلة --}} <button type="button" class="product-cart-btn cart-add-btn"
                                data-product-id="{{ $product->ProductID }}" 
                                data-product-name="{{ $product->ProductName }}"
                                data-product-title="{{ $product->ProductTitle }}"
                                data-product-type="{{ $product->ProductType }}" data-product-tags="{{ $product->tags }}"
                                data-product-picture="{{ $product->ProductPicture }}"> <span>إضافة إلى السلة</span>
                                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4" />
                                    <line x1="3" y1="6" x2="21" y2="6" />
                                    <path d="M16 10a4 4 0 01-8 0" />
                                </svg> </button> </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div> {{-- ========================================= Tags Popup ========================================= --}} <div class="tags-popup-overlay" id="tagsPopupOverlay"> </div>
    <div class="tags-popup" id="tagsPopup"> <button type="button" class="tags-popup-close" id="tagsPopupClose"> &times;
        </button>
        <h3 class="tags-popup-title"> <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
                <line x1="7" y1="7" x2="7.01" y2="7" />
            </svg> <span>مواصفات المنتج</span> </h3>
        <div class="tags-popup-list" id="tagsPopupList"> </div>
    </div>




    {{-- ========================= --}}
    {{-- Cart Drawer               --}}
    {{-- ========================= --}}
    <div class="cart-drawer-overlay" id="cartDrawerOverlay"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-drawer-header">
            <h3 class="cart-drawer-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                </svg>
                <span>سلة التسوق</span>
            </h3>
            <button class="cart-drawer-close" id="cartDrawerClose">&times;</button>
        </div>

        <div class="cart-drawer-body" id="cartDrawerBody">
            <div class="cart-empty" id="cartEmpty">
                <div class="cart-empty-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                    </svg>
                </div>
                <p class="cart-empty-text">سلتك فارغة حالياً</p>
                <p class="cart-empty-sub">أضف منتجات للبدء بالتسوق</p>
            </div>
        </div>

        <div class="cart-drawer-footer" id="cartDrawerFooter" style="display: none;">
            <div class="cart-summary">
                <span class="cart-summary-label">عدد القطع</span>
                <span class="cart-summary-value" id="cartTotalItems">0</span>
            </div>
            <div class="cart-drawer-actions">
                <button class="cart-action-btn cart-view-btn" id="cartViewReceiptBtn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    <span>مشاهدة الفاتورة</span>
                </button>
                <button class="cart-action-btn cart-send-btn" id="cartSendBtn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13" />
                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                    </svg>
                    <span>إرسال الطلب</span>
                </button>
                <button class="cart-action-btn cart-cancel-btn" id="cartCancelBtn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                    </svg>
                    <span>إفراغ السلة</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- Receipt Modal             --}}
    {{-- ========================= --}}
    <form action=" {{ Route('main.order') }} " method="POST" id="orderForm">
        @csrf
        <div class="modal-overlay" id="receiptModal">
            <div class="modal-box receipt-box">
                <button type="button" class="modal-close" data-close="receiptModal">&times;</button>

                <div id="receiptContent" class="orderFormWrapper">
                    <h2 class="modal-title">فاتورة الطلب</h2>
                    <p class="modal-subtitle">البلاد للكهربائيات</p>

                    <div class="receipt-meta">
                        <div class="receipt-meta-row">
                            <span class="receipt-meta-label">رقم الطلب</span>
                            <span class="receipt-meta-value" id="receiptOrderNo">—</span>
                        </div>
                        <div class="receipt-meta-row">
                            <span class="receipt-meta-label">التاريخ</span>
                            <span class="receipt-meta-value" id="receiptDate">—</span>
                        </div>
                    </div>

                    <div class="receipt-divider"></div>

                    <div id="receiptItems"></div>

                    <div class="receipt-divider"></div>

                    <div class="receipt-total">
                        <span>إجمالي عدد القطع</span>
                        <span id="receiptTotalItems">0</span>
                    </div>

                    <div class="receipt-note">
                        هذه الفاتورة محفوظة ويمكن مراجعتها لاحقاً
                    </div>

                    <div class="receipt-actions">
                        <button type="submit" class="modal-send-btn" id="receiptSendBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                            <span>إرسال الطلب</span>
                        </button>
                        <button type="button" class="receipt-print-btn" id="receiptPrintBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9" />
                                <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                                <rect x="6" y="14" width="12" height="8" />
                            </svg>
                            <span>طباعة</span>
                        </button>
                    </div>
                </div>

                <div class="modal-success" id="receiptSuccess">
                    <div class="success-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <div class="success-text">تم إرسال طلبك بنجاح</div>
                    <div class="success-sub">سيتم التواصل معك قريباً لتأكيد الطلب</div>
                </div>
            </div>
        </div>
    </form>



    {{-- ========================= --}}
    {{-- Customer Info Modal       --}}
    {{-- ========================= --}}
    <div class="modal-overlay" id="customerInfoModal">
        <div class="modal-box">
            <button class="modal-close" data-close="customerInfoModal">&times;</button>

            <div class="modal-form-wrapper" id="customerInfoWrapper">
                <h2 class="modal-title">معلومات الطلب</h2>
                <p class="modal-subtitle">يرجى إدخال بياناتك لإتمام عملية الإرسال</p>

                <form id="customerInfoForm">
                    <div class="modal-field">
                        <label for="custName">اسم العميل *</label>
                        <input type="text" id="custName" name="custName" placeholder="الاسم الكامل" required>
                    </div>

                    <div class="modal-field">
                        <label for="custPhone">رقم الهاتف *</label>
                        <input type="tel" id="custPhone" name="custPhone" placeholder="07XXXXXXXXX" required>
                    </div>

                    <div class="modal-field">
                        <label for="custShop">اسم المحل</label>
                        <input type="text" id="custShop" name="custShop" placeholder="اسم المحل (إن وجد)">
                    </div>

                    <div class="modal-field">
                        <label for="custAddress">شرح تفصيلي لموقع المحل *</label>
                        <textarea id="custAddress" name="custAddress" placeholder="مثال: الشارع الأوسط، بجانب صيدلية الرابع..." required></textarea>
                    </div>

                    <button type="button" class="modal-send-btn" id="customerInfoSubmit">
                        <span>تأكيد</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- ========================= --}}
    {{-- Saved Orders Modal        --}}
    {{-- ========================= --}}
    <div class="modal-overlay" id="savedOrdersModal">
        <div class="modal-box">
            <button class="modal-close" data-close="savedOrdersModal">&times;</button>
            <h2 class="modal-title">الطلبات المحفوظة</h2>
            <p class="modal-subtitle">يمكنك مراجعة طلباتك السابقة</p>
            <div id="savedOrdersList"></div>
        </div>
    </div>


    {{-- ========================= --}}
    {{-- Mobile Bottom Bar        --}}
    {{-- ========================= --}}
    <div class="mobile-bottom-bar" id="mobileBottomBar">
        <a href="https://wa.me/967771429866" class="bottom-bar-item bottom-bar-whatsapp" target="_blank" rel="noopener">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
            <span>واتساب</span>
        </a>
        <button class="bottom-bar-item bottom-bar-pdf" id="bottomBarPdf">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            <span>تحميل القائمة</span>
        </button>
        <button class="bottom-bar-item bottom-bar-metals" id="bottomBarMetals">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5z" />
                <path d="M2 17l10 5 10-5" />
                <path d="M2 12l10 5 10-5" />
            </svg>
            <span>أسعار المعادن</span>
        </button>
        <button class="bottom-bar-item bottom-bar-cart" id="bottomBarCart">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
            </svg>
            <span>السلة</span>
            <span class="bottom-bar-badge" id="bottomBarBadge">0</span>
        </button>
    </div>


    {{-- ========================= --}}
    {{-- Metal Prices Modal        --}}
    {{-- ========================= --}}
    <div class="metals-overlay" id="metalsOverlay">
        <div class="metals-panel" id="metalsPanel">
            <button class="metals-close" id="metalsClose">&times;</button>

            <div class="metals-panel-header">
                <div class="metals-header-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                        <path d="M2 17l10 5 10-5" />
                        <path d="M2 12l10 5 10-5" />
                    </svg>
                </div>
                <h2 class="metals-title">أسعار المعادن</h2>
                <p class="metals-subtitle">الأسعار محدّثة يومياً</p>
            </div>

            <div class="metals-grid" id="metalsGrid"></div>

            <div class="metals-footer">
                <span class="metals-updated" id="metalsUpdated">آخر تحديث: —</span>
                <button class="metals-refresh-btn" id="metalsRefreshBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10" />
                        <polyline points="1 20 1 14 7 14" />
                        <path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15" />
                    </svg>
                    <span>تحديث</span>
                </button>
            </div>
        </div>
    </div>
@endsection
