@php
    $titlePage = $product ? 'تعديل منتج' : 'إضافة منتج';
@endphp

@extends('layouts.app', ['titlePage' => $titlePage])

@section('myapp')
    <div class="manage-page">
        <div class="manage-header">
            <div class="manage-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z" />
                    <path d="M2 17l10 5 10-5" />
                    <path d="M2 12l10 5 10-5" />
                </svg>
            </div>
            <h1 class="manage-title">{{ $product ? 'تعديل المنتج' : 'منتج جديد' }}</h1>
            <p class="manage-subtitle">{{ $product ? 'قم بتعديل المعلومات أدناه' : 'أدخل معلومات المنتج الجديد' }}</p>
        </div>

        <form class="manage-form"
            action="{{ $product ? route('manage.update', $product->ProductID) : route('manage.store') }}" method="POST"
            enctype="multipart/form-data" id="manageForm">

            @csrf
            @if ($product)
                @method('PUT')
            @else
                @method('POST')
            @endif

            <div class="manage-grid">

                {{-- Left Column: Inputs --}}
                <div class="manage-col-left">

                    <div class="manage-card-section">
                        <div class="section-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 7h-9"></path>
                                <path d="M14 17H5"></path>
                                <circle cx="17" cy="17" r="3"></circle>
                                <circle cx="7" cy="7" r="3"></circle>
                            </svg>
                            <span>المعلومات الأساسية</span>
                        </div>

                        <div class="manage-field">
                            <label for="productName">اسم المنتج</label>
                            <input type="text" id="productName" name="ProductName" required autocomplete="off"
                                placeholder="مثال: كابل نحاس 2.5mm" value="{{ $product->ProductName ?? '' }}">
                        </div>

                        <div class="manage-field">
                            <label for="productTitle">عنوان المنتج</label>
                            <input type="text" id="productTitle" name="ProductTitle" required autocomplete="off"
                                placeholder="مثال: كابل كهربائي مجوز طول 100 قدم" value="{{ $product->ProductTitle ?? '' }}">
                        </div>

                        <div class="manage-field-group">
                            <div class="manage-field manage-field-half">
                                <label for="mainNumber">نوع المنتج</label>
                                <input type="text" id="mainNumber" name="mainNumber"
                                    lang="en" placeholder="مثال: كابلات كهربائية" value="{{ $product->ProductType ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="manage-card-section">
                        <div class="section-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
                                <line x1="7" y1="7" x2="7.01" y2="7" />
                            </svg>
                            <span>كلمات مفتاحية (Tags)</span>
                        </div>

                        <p class="tags-hint">أضف مواصفات المنتج ككلمات مفتاحية — مثل: طول 100م، عيار 2.5mm، لون أحمر</p>

                        <div class="tags-input-row">
                            <input type="text" id="tagInput" placeholder="اكتب كلمة ثم اضغط Enter" autocomplete="off">
                            <button type="button" class="tag-add-btn" id="tagAddBtn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </button>
                        </div>

                        <div class="tags-container" id="tagsContainer">
                            @if ($product)
                                @foreach ($product->tags as $tag)
                                    <span class="tag-chip">
                                        <span class="tag-chip-text">{{ $tag->Tag }}</span>
                                        <button type="button" class="tag-chip-remove">&times;</button>
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        <input type="hidden" name="tags" id="hiddenTags" value="">
                    </div>
                </div>

                {{-- Right Column: Image Upload + Submit --}}
                <div class="manage-col-right">

                    <div class="manage-card-section">
                        <div class="section-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                            <span>صورة المنتج</span>
                        </div>

                        <label class="upload-area" for="fileInput">
                            <div class="upload-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                            </div>
                            <div class="upload-text" id="uploadText">
                                {{ !empty($product) && $product->ProductPicture ? 'تم تحميل صورة' : 'اسحب صورة هنا أو اضغط للاختيار' }}
                            </div>
                            <div class="upload-sub" id="uploadSub">
                                {{ !empty($product) && $product->ProductPicture ? 'يمكنك تغيير الصورة بالنقر' : 'PNG, JPG — حتى 5MB' }}
                            </div>
                            @if (!empty($product) && $product->ProductPicture)
                                <div class="upload-preview">
                                    <img src="{{ asset('assets/uploads/' . $product->ProductPicture) }}" alt="Preview">
                                </div>
                            @endif
                            <input type="file" id="fileInput" name="image" accept="image/*">
                        </label>
                    </div>

                    <div class="manage-submit-section">
                        <button type="submit" class="manage-submit-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span>{{ $product ? 'حفظ التعديلات' : 'إنشاء المنتج' }}</span>
                        </button>
                        <button type="button" class="manage-clear-btn" id="clearBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                            </svg>
                            <span>مسح الكل</span>
                        </button>
                    </div>
                </div>

            </div>

            <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
        </form>
    </div>

    <script src="{{ asset('assets/js/manage.js') }}"></script>
@endsection
