<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm p-10 sm:rounded-lg">
                {{-- Error Summary Alert --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h1 class="text-indigo-950 font-bold text-3xl mb-4">Edit Product</h1>

                    {{-- Product name --}}
                    <div class="mt-4">
                        <x-input-label for="name" :value="__('Product name')" />
                        <x-text-input id="name" class="block mt-1 w-full @error('name') border-red-500 @enderror"
                            type="text" name="name" :value="old('name', $product->name)" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea name="about" id="description" rows="4"
                            class="w-full py-2 px-3 border rounded-lg @error('about') border-red-500 @enderror">{{ old('about', $product->about) }}</textarea>
                        <x-input-error :messages="$errors->get('about')" class="mt-2" />
                    </div>

                    {{-- Price --}}
                    <div class="mt-4">
                        <x-input-label for="price" :value="__('Price (Rp)')" />
                        <x-text-input id="price" class="block mt-1 w-full @error('price') border-red-500 @enderror"
                            type="number" name="price" :value="old('price', $product->price)" required autocomplete="price"
                            placeholder="0" min="0" />
                        <p class="text-xs text-gray-500 mt-1">Set to 0 for free products</p>
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    {{-- Category --}}
                    <div class="mt-4">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select name="category_id" id="category_id"
                            class="w-full py-2 pl-5 border rounded-lg @error('category_id') border-red-500 @enderror">
                            <option value="">Select category</option>
                            @forelse ($catagories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @empty
                                <option disabled>Data kategori tidak ada</option>
                            @endforelse
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    {{-- Thumbnail/Cover with Preview --}}
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-3">
                            <x-input-label for="cover" :value="__('Thumbnail')" />
                            <span class="text-xs text-gray-500">Max 2MB • PNG, JPG, JPEG</span>
                        </div>

                        <div class="relative">
                            <input type="file" id="cover" name="cover" accept="image/png,image/jpeg,image/jpg"
                                class="hidden" onchange="previewThumbnail(event)">

                            <label for="cover" id="thumbnail-label"
                                class="flex flex-col items-center justify-center w-full aspect-[16/9]
                                       border-2 border-dashed @error('cover') border-red-300 @else border-gray-300 @enderror rounded-2xl cursor-pointer
                                       bg-gradient-to-br from-gray-50 to-gray-100 hover:from-indigo-50 hover:to-blue-50
                                       transition-all duration-300 overflow-hidden group relative">

                                <div id="upload-placeholder" class="{{ $product->cover ? 'hidden' : 'flex' }} flex-col items-center justify-center p-8 text-center">
                                    <div class="w-20 h-20 mb-4 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600
                                                flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-700 mb-2">
                                        Click to upload or drag & drop
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Recommended: 1208x840px • Max 2MB
                                    </p>
                                </div>

                                <img id="thumbnail-preview"
                                     src="{{ $product->cover ? Storage::url($product->cover) : '' }}"
                                     class="{{ $product->cover ? '' : 'hidden' }} w-full h-full object-cover"
                                     alt="Thumbnail preview">

                                <div id="preview-overlay"
                                     class="{{ $product->cover ? '' : 'hidden' }} absolute inset-0 bg-gradient-to-t from-black/60 to-transparent
                                            group-hover:opacity-100 opacity-0 transition-opacity duration-300
                                            flex items-end justify-center pb-6">
                                    <span class="text-white font-semibold text-lg px-6 py-2 bg-white/20 rounded-full backdrop-blur-sm">
                                        Change Image
                                    </span>
                                </div>
                            </label>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">Leave empty if you don't want to change the thumbnail.</p>
                        <x-input-error :messages="$errors->get('cover')" class="mt-2" />
                    </div>

                    {{-- Detail Images --}}
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-3">
                            <x-input-label for="detail_images" :value="__('Detail Images (1-4 images)')" />
                            <span class="text-xs text-gray-500">Max 4MB per image • PNG, JPG, JPEG</span>
                        </div>

                        {{-- Current Images Preview --}}
                        @php
                            $currentImages = $product->detail_images
                                ? (is_array($product->detail_images) ? $product->detail_images : json_decode($product->detail_images, true))
                                : [];
                        @endphp

                        @if(count($currentImages) > 0)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-600 mb-2">Current Images:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($currentImages as $index => $image)
                                        <div class="relative aspect-[4/3] rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ Storage::url($image) }}" class="w-full h-full object-cover" alt="Detail {{ $index + 1 }}">
                                            <div class="absolute bottom-1 left-1 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file" id="detail_images" name="detail_images[]" accept="image/png,image/jpeg,image/jpg"
                                class="hidden" multiple onchange="handleDetailImages(event)">

                            <label for="detail_images" id="detail-images-label"
                                class="flex flex-col items-center justify-center w-full min-h-[200px]
                                       border-2 border-dashed @error('detail_images') border-red-300 @else border-gray-300 @enderror rounded-2xl cursor-pointer
                                       bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100
                                       transition-all duration-300 p-6 group">

                                <div class="w-16 h-16 mb-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600
                                            flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                <p class="text-base font-semibold text-gray-700 mb-1">
                                    Upload New Detail Images
                                </p>
                                <p class="text-sm text-gray-500 text-center">
                                    Select 1-4 images to replace current ones
                                </p>
                            </label>

                            <div id="detail-images-preview" class="hidden grid grid-cols-2 gap-4 mt-4"></div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">Leave empty if you don't want to change the detail images.</p>
                        <x-input-error :messages="$errors->get('detail_images')" class="mt-2" />
                    </div>

                    {{-- Google Drive Link --}}
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-2">
                            <x-input-label for="file_url" :value="__('Product File (Google Drive Link)')" />
                        </div>

                        {{-- Current Link Info --}}
                        @if($product->file_url)
                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm text-green-700">
                                        <span class="font-semibold">Current link:</span>
                                        <a href="{{ $product->file_url }}" target="_blank" class="underline hover:text-green-900 break-all">
                                            {{ Str::limit($product->file_url, 50) }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="relative">
                            <div class="flex items-center w-full p-4 border-2 border-gray-200 rounded-xl
                                        bg-gradient-to-r from-purple-50 to-pink-50 focus-within:border-indigo-500
                                        focus-within:ring-2 focus-within:ring-indigo-200 transition-all duration-300">

                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600
                                            flex items-center justify-center shadow-md flex-shrink-0 mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <input type="url" id="file_url" name="file_url"
                                        class="w-full bg-transparent border-none focus:ring-0 text-gray-700 placeholder-gray-400 p-0
                                               @error('file_url') text-red-500 @enderror"
                                        placeholder="https://drive.google.com/file/d/xxxxx/view"
                                        value="{{ old('file_url', $product->file_url) }}"
                                        required />
                                    <p class="text-xs text-gray-500 mt-1">
                                        Update your Google Drive sharing link
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Help text --}}
                        <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-xs text-amber-800">
                                    <p class="font-semibold mb-1">How to get Google Drive link:</p>
                                    <ol class="list-decimal list-inside space-y-0.5 text-amber-700">
                                        <li>Upload your ZIP file to Google Drive</li>
                                        <li>Right-click the file → "Share"</li>
                                        <li>Set access to "Anyone with the link"</li>
                                        <li>Copy and paste the link here</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('file_url')" class="mt-2" />
                    </div>

                    {{-- File formats --}}
                    <div class="mt-6">
                        <x-input-label for="file_formats" :value="__('File Formats Included')" class="mb-3" />

                        @php
                            $availableFormats = [
                                'figma' => ['name' => 'Figma', 'logo' => 'figma-logo.svg'],
                                'framer' => ['name' => 'Framer', 'logo' => 'framer.png'],
                                'illustrator' => ['name' => 'Illustrator', 'logo' => 'illustrator.svg'],
                                'laravel' => ['name' => 'Laravel', 'logo' => 'Laravel.svg'],
                                'python' => ['name' => 'Python', 'logo' => 'Python.svg'],
                                'html' => ['name' => 'HTML', 'logo' => 'html.svg'],
                                'react_js' => ['name' => 'React JS', 'logo' => 'reactJS.svg'],
                                'golang' => ['name' => 'Golang', 'logo' => 'golang.svg'],
                                'flutter' => ['name' => 'Flutter', 'logo' => 'flutter.svg'],
                            ];

                            $selectedFormats = old('file_formats');
                            if (is_null($selectedFormats)) {
                                $selectedFormats = $product->file_formats
                                    ? explode(',', $product->file_formats)
                                    : [];
                            }
                        @endphp

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($availableFormats as $value => $format)
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="file_formats[]" value="{{ $value }}"
                                        class="peer sr-only" @checked(in_array($value, $selectedFormats))>

                                    <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl
                                                transition-all duration-200 ease-in-out
                                                peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:shadow-md
                                                hover:border-gray-300 peer-checked:hover:border-indigo-600">

                                        <div class="flex-shrink-0 w-6 h-6">
                                            <img src="{{ asset('images/logos/' . $format['logo']) }}"
                                                alt="{{ $format['name'] }}" class="w-full h-full object-contain">
                                        </div>

                                        <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-900">
                                            {{ $format['name'] }}
                                        </span>

                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <x-input-error :messages="$errors->get('file_formats')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-3">
                        <a href="{{ route('admin.products.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Update Product') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        // Thumbnail Preview
        function previewThumbnail(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    const preview = document.getElementById('thumbnail-preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    document.getElementById('preview-overlay').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Detail Images
        let detailImagesDataTransfer = new DataTransfer();
        const MAX_DETAIL_IMAGES = 4;

        function handleDetailImages(event) {
            const files = Array.from(event.target.files);
            const currentCount = detailImagesDataTransfer.files.length;

            if (currentCount + files.length > MAX_DETAIL_IMAGES) {
                alert(`Maximum ${MAX_DETAIL_IMAGES} images allowed. You currently have ${currentCount} image(s).`);
                event.target.value = '';
                return;
            }

            files.forEach(file => {
                if (detailImagesDataTransfer.files.length < MAX_DETAIL_IMAGES) {
                    detailImagesDataTransfer.items.add(file);
                }
            });

            document.getElementById('detail_images').files = detailImagesDataTransfer.files;
            renderDetailImagesPreviews();
        }

        function renderDetailImagesPreviews() {
            const previewContainer = document.getElementById('detail-images-preview');
            const uploadLabel = document.getElementById('detail-images-label');
            const files = detailImagesDataTransfer.files;

            if (files.length === 0) {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
                uploadLabel.classList.remove('hidden');
                return;
            }

            uploadLabel.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            previewContainer.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageCard = document.createElement('div');
                    imageCard.className = 'relative group aspect-[4/3] rounded-xl overflow-hidden border-2 border-gray-200 shadow-sm hover:shadow-md transition-shadow';
                    imageCard.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover" alt="Detail image ${index + 1}">
                        <button type="button" onclick="removeDetailImage(${index})"
                            class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full
                                   opacity-0 group-hover:opacity-100 transition-all duration-200
                                   flex items-center justify-center hover:bg-red-600 hover:scale-110 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="absolute bottom-2 left-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                            New ${index + 1}
                        </div>
                    `;
                    previewContainer.appendChild(imageCard);
                };
                reader.readAsDataURL(file);
            });

            if (files.length < MAX_DETAIL_IMAGES) {
                const addMoreCard = document.createElement('label');
                addMoreCard.htmlFor = 'detail_images';
                addMoreCard.className = 'relative aspect-[4/3] rounded-xl border-2 border-dashed border-gray-300 cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 flex flex-col items-center justify-center bg-gray-50 group';
                addMoreCard.innerHTML = `
                    <svg class="w-12 h-12 text-gray-400 group-hover:text-indigo-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-600 group-hover:text-indigo-600">Add More</span>
                    <span class="text-xs text-gray-500 mt-1">${files.length}/${MAX_DETAIL_IMAGES}</span>
                `;
                previewContainer.appendChild(addMoreCard);
            }
        }

        function removeDetailImage(index) {
            const newDataTransfer = new DataTransfer();
            const files = Array.from(detailImagesDataTransfer.files);
            files.forEach((file, i) => {
                if (i !== index) newDataTransfer.items.add(file);
            });
            detailImagesDataTransfer = newDataTransfer;
            document.getElementById('detail_images').files = detailImagesDataTransfer.files;
            renderDetailImagesPreviews();
        }
    </script>
</x-app-layout>
