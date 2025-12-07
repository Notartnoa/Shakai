<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="p-2 hover:bg-[#2A2A2A] rounded-lg transition-colors">
                <svg class="w-5 h-5 text-[#A0A0A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-white">
                Edit Product
            </h2>
        </div>
    </x-slot>

    {{-- Error Summary Alert --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/50 p-4 rounded-xl">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-red-400 font-semibold mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info Card --}}
                <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
                    <h3 class="text-lg font-semibold text-white mb-6">Basic Information</h3>

                    {{-- Product name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#A0A0A0] mb-2">Product Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required autofocus
                            class="w-full px-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#2D68F8] focus:ring-1 focus:ring-[#2D68F8] transition-colors @error('name') border-red-500 @enderror"
                            placeholder="Enter product name">
                        @error('name')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mt-5">
                        <label for="about" class="block text-sm font-medium text-[#A0A0A0] mb-2">Description</label>
                        <textarea name="about" id="about" rows="5"
                            class="w-full px-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#2D68F8] focus:ring-1 focus:ring-[#2D68F8] transition-colors resize-none @error('about') border-red-500 @enderror"
                            placeholder="Describe your product...">{{ old('about', $product->about) }}</textarea>
                        @error('about')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Price & Category Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        {{-- Price --}}
                        <div>
                            <label for="price" class="block text-sm font-medium text-[#A0A0A0] mb-2">Price (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-[#595959]">Rp</span>
                                </div>
                                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0"
                                    class="w-full pl-12 pr-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#2D68F8] focus:ring-1 focus:ring-[#2D68F8] transition-colors @error('price') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            <p class="text-xs text-[#595959] mt-2">Set to 0 for free products</p>
                            @error('price')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-[#A0A0A0] mb-2">Category</label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white focus:outline-none focus:border-[#2D68F8] focus:ring-1 focus:ring-[#2D68F8] transition-colors @error('category_id') border-red-500 @enderror">
                                <option value="" class="bg-[#2A2A2A]">Select category</option>
                                @forelse ($catagories as $category)
                                    <option value="{{ $category->id }}" class="bg-[#2A2A2A]"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @empty
                                    <option disabled class="bg-[#2A2A2A]">No categories available</option>
                                @endforelse
                            </select>
                            @error('category_id')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Images Card --}}
                <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
                    <h3 class="text-lg font-semibold text-white mb-6">Product Images</h3>

                    {{-- Thumbnail/Cover --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-[#A0A0A0]">Thumbnail</label>
                            <span class="text-xs text-[#595959]">Max 2MB • PNG, JPG, JPEG</span>
                        </div>

                        <div class="relative">
                            <input type="file" id="cover" name="cover" accept="image/png,image/jpeg,image/jpg"
                                class="hidden" onchange="previewThumbnail(event)">

                            <label for="cover" id="thumbnail-label"
                                class="flex flex-col items-center justify-center w-full aspect-[16/9]
                                       border-2 border-dashed border-[#414141] rounded-2xl cursor-pointer
                                       bg-[#2A2A2A] hover:bg-[#363636] hover:border-[#595959]
                                       transition-all duration-300 overflow-hidden group relative
                                       @error('cover') border-red-500 @enderror">

                                <div id="upload-placeholder" class="{{ $product->cover ? 'hidden' : 'flex' }} flex-col items-center justify-center p-8 text-center">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-[#2D68F8]/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-[#2D68F8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-base font-medium text-white mb-1">Click to upload thumbnail</p>
                                    <p class="text-sm text-[#595959]">Recommended: 1208x840px</p>
                                </div>

                                <img id="thumbnail-preview"
                                     src="{{ $product->cover ? Storage::url($product->cover) : '' }}"
                                     class="{{ $product->cover ? '' : 'hidden' }} w-full h-full object-cover"
                                     alt="Thumbnail preview">

                                <div id="preview-overlay"
                                     class="{{ $product->cover ? 'flex' : 'hidden' }} absolute inset-0 bg-gradient-to-t from-black/70 to-transparent
                                            opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                            items-end justify-center pb-6">
                                    <span class="text-white font-medium text-sm px-4 py-2 bg-white/20 rounded-full backdrop-blur-sm">
                                        Change Image
                                    </span>
                                </div>
                            </label>
                        </div>
                        <p class="text-xs text-[#595959] mt-2">Leave empty to keep current thumbnail</p>
                        @error('cover')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Current Detail Images --}}
                    @php
                        $currentImages = $product->detail_images
                            ? (is_array($product->detail_images) ? $product->detail_images : json_decode($product->detail_images, true))
                            : [];
                    @endphp

                    @if(count($currentImages) > 0)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-[#A0A0A0] mb-3">Current Detail Images</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($currentImages as $index => $image)
                                    <div class="relative aspect-[4/3] rounded-xl overflow-hidden border border-[#414141] bg-[#2A2A2A]">
                                        <img src="{{ Storage::url($image) }}" class="w-full h-full object-cover" alt="Detail {{ $index + 1 }}">
                                        <div class="absolute bottom-1.5 left-1.5 bg-[#2D68F8] text-white text-xs font-medium px-2 py-0.5 rounded-full">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upload New Detail Images --}}
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-[#A0A0A0]">Upload New Detail Images (1-4)</label>
                            <span class="text-xs text-[#595959]">Max 4MB per image</span>
                        </div>

                        <div class="relative">
                            <input type="file" id="detail_images" name="detail_images[]" accept="image/png,image/jpeg,image/jpg"
                                class="hidden" multiple onchange="handleDetailImages(event)">

                            <label for="detail_images" id="detail-images-label"
                                class="flex flex-col items-center justify-center w-full min-h-[150px]
                                       border-2 border-dashed border-[#414141] rounded-2xl cursor-pointer
                                       bg-[#2A2A2A] hover:bg-[#363636] hover:border-[#595959]
                                       transition-all duration-300 p-6 group
                                       @error('detail_images') border-red-500 @enderror">

                                <div class="w-12 h-12 mb-3 rounded-xl bg-purple-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                <p class="text-sm font-medium text-white mb-1">Replace Detail Images</p>
                                <p class="text-xs text-[#595959] text-center">Select new images to replace current ones</p>
                            </label>

                            <div id="detail-images-preview" class="hidden grid grid-cols-2 gap-3 mt-4"></div>
                        </div>
                        <p class="text-xs text-[#595959] mt-2">Leave empty to keep current images</p>
                        @error('detail_images')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Right Column - Sidebar --}}
            <div class="space-y-6">
                {{-- Product File Card --}}
                <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Product File</h3>

                    {{-- Current Link Info --}}
                    @if($product->file_url)
                        <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-emerald-400 mb-1">Current link:</p>
                                    <a href="{{ $product->file_url }}" target="_blank" class="text-xs text-emerald-400/80 hover:text-emerald-300 break-all underline">
                                        {{ Str::limit($product->file_url, 40) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="file_url" class="block text-sm font-medium text-[#A0A0A0] mb-2">Google Drive Link</label>
                        <input type="url" id="file_url" name="file_url" value="{{ old('file_url', $product->file_url) }}" required
                            class="w-full px-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#2D68F8] focus:ring-1 focus:ring-[#2D68F8] transition-colors text-sm @error('file_url') border-red-500 @enderror"
                            placeholder="https://drive.google.com/...">
                        @error('file_url')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Help text --}}
                    <div class="mt-4 p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-xs text-amber-400/80">
                                <p class="font-semibold text-amber-400 mb-1">How to get link:</p>
                                <ol class="list-decimal list-inside space-y-0.5">
                                    <li>Upload to Google Drive</li>
                                    <li>Right-click → Share</li>
                                    <li>Set "Anyone with link"</li>
                                    <li>Copy link here</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- File Formats Card --}}
                <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">File Formats</h3>

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

                    <div class="space-y-2">
                        @foreach ($availableFormats as $value => $format)
                            <label class="relative cursor-pointer group block">
                                <input type="checkbox" name="file_formats[]" value="{{ $value }}"
                                    class="peer sr-only" @checked(in_array($value, $selectedFormats))>

                                <div class="flex items-center gap-3 px-3 py-2.5 bg-[#2A2A2A] border border-[#414141] rounded-lg
                                            transition-all duration-200 ease-in-out
                                            peer-checked:bg-[#2D68F8]/10 peer-checked:border-[#2D68F8]
                                            hover:border-[#595959] group-hover:bg-[#363636]">

                                    <div class="flex-shrink-0 w-5 h-5">
                                        <img src="{{ asset('images/logos/' . $format['logo']) }}"
                                            alt="{{ $format['name'] }}" class="w-full h-full object-contain">
                                    </div>

                                    <span class="text-sm text-[#A0A0A0] peer-checked:text-white flex-1">
                                        {{ $format['name'] }}
                                    </span>

                                    <div class="opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4 text-[#2D68F8]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('file_formats')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Card --}}
                <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
                    <button type="submit"
                        class="w-full py-3 bg-[#2D68F8] hover:bg-[#083297] text-white font-semibold rounded-xl transition-colors">
                        Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                        class="w-full mt-3 py-3 border border-[#414141] rounded-xl text-[#A0A0A0] hover:bg-[#2A2A2A] hover:text-white transition-colors text-center block">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

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
                    document.getElementById('preview-overlay').classList.add('flex');
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
                    imageCard.className = 'relative group aspect-[4/3] rounded-xl overflow-hidden border border-[#414141] bg-[#2A2A2A]';
                    imageCard.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover" alt="Detail image ${index + 1}">
                        <button type="button" onclick="removeDetailImage(${index})"
                            class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-full
                                   opacity-0 group-hover:opacity-100 transition-all duration-200
                                   flex items-center justify-center hover:bg-red-600 shadow-lg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="absolute bottom-1.5 left-1.5 bg-emerald-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">
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
                addMoreCard.className = 'relative aspect-[4/3] rounded-xl border-2 border-dashed border-[#414141] cursor-pointer hover:border-[#2D68F8] hover:bg-[#2D68F8]/10 transition-all duration-200 flex flex-col items-center justify-center bg-[#2A2A2A] group';
                addMoreCard.innerHTML = `
                    <svg class="w-8 h-8 text-[#595959] group-hover:text-[#2D68F8] mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-xs text-[#595959] group-hover:text-[#2D68F8]">${files.length}/${MAX_DETAIL_IMAGES}</span>
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
