@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
    <style>
        .image-uploader {
            border: 2px dashed #aaa;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            border-radius: 9px;
            background: #fafaf9;
        }

        .image-uploader img {
            max-width: 100%;
            max-height: 200px;
            margin-bottom: 1rem;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            width: 69px;
            margin-left: 4px;
            display: inline;
            margin-right: 1rem;
        }
    </style>

    <div class="min-h-screen h-full flex flex-col after:flex-1">


        <!-- Header -->

        <!-- Progress bar -->
        <div class="px-4 py-8">
            <div class="max-w-md mx-auto">

                <h1 class="text-3xl text-slate-800 font-bold mb-6">Upload PDF files as sources ✨</h1>
                <!-- Form -->
                @if ($errors->has('pdffiles'))
                    <div x-show="open" x-data="{ open: true }" style="margin-bottom: 1rem;">
                        <div class="px-4 py-2 rounded-sm text-sm bg-amber-100 border border-amber-200 text-amber-600">
                            <div class="flex w-full justify-between items-start">
                                <div class="flex">
                                    <svg class="w-4 h-4 shrink-0 fill-current opacity-80 mt-[3px] mr-3"
                                         viewBox="0 0 16 16">
                                        <path
                                            d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"></path>
                                    </svg>
                                    <div>
                                    </div>
                                </div>
                                <button class="opacity-70 hover:opacity-80 ml-3 mt-[3px]" @click="open = false">
                                    <div class="sr-only">Close</div>
                                    <svg class="w-4 h-4 fill-current">
                                        <path
                                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                <form action="{{route('onboarding.other-data-sources-pdf.create', ['id' => request()->route('id')])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4 mb-8">
                        <!-- Company Name -->
                        <div>
                            <div class="image-uploader" id="imageUploader">
                                <div class="emoji" style="font-size: 30px">
                                    ⬆️
                                </div>
                                <p style="font-weight: bold; margin-bottom: 1rem"> Click to upload or drag & drop

                                </p>
                                <span
                                    style="color: #2563eb">
                                        you can upload up to 5 pdf files, and we will process the first ±100k words</span>
                            </div>
                            <input type="file" name="pdffiles[]" id="fileInput" style="display: none" required accept="application/pdf"
                                   multiple>


                            <div class="uploaded-images" id="uploadedImages" style="margin-top: 1rem">

                            </div>

                        </div>

                        <div class="flex items-center justify-between space-x-6 mb-8">
                            <div>
                                <div class="font-medium text-slate-800 text-sm mb-1">Make sure that your files are scannable (text not images) 🫶
                                </div>
                                <div class="text-xs">
                                    You can upload multiple files at once and we will process them in the background.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <a class="text-sm underline hover:no-underline" href="{{route('onboarding.welcome')}}">&lt;-
                            Back</a>
                        <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-auto">Next Step
                            -&gt;
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const imageUploader = document.getElementById('imageUploader');
        const fileInput = document.getElementById('fileInput');
        const uploadedImages = document.getElementById('uploadedImages');

        imageUploader.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (event) => {
            const files = event.target.files;
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    const imgElement = document.createElement('img');
                    imgElement.src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/833px-PDF_file_icon.svg.png';
                    imgElement.classList.add('img-thumbnail', 'mr-2', 'mb-2');
                    uploadedImages.appendChild(imgElement);
                };
            });
        });

        imageUploader.addEventListener('dragover', (event) => {
            event.preventDefault();
            imageUploader.classList.add('border-primary');
        });

        imageUploader.addEventListener('dragleave', (event) => {
            event.preventDefault();
            imageUploader.classList.remove('border-primary');
        });

        imageUploader.addEventListener('drop', (event) => {
            event.preventDefault();
            imageUploader.classList.remove('border-primary');
            const files = event.dataTransfer.files;
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    const imgElement = document.createElement('img');
                    imgElement.src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/833px-PDF_file_icon.svg.png';
                    imgElement.classList.add('img-thumbnail', 'mr-2', 'mb-2');
                    uploadedImages.appendChild(imgElement);
                    // add the image to the file input
                };
                fileInput.files = files;
            });
        });

        const fileInputs = document.getElementById('fileInput');

        function showLoading() {
            // Get submit button and loading icon
            const submitBtn = document.getElementById('submitBtn');

            if (fileInputs.files.length > 0) {
                document.getElementById('submitBtn').setAttribute('disabled', 'true');
                document.getElementById('loadingIcon').classList.remove('d-none');
                submitBtn.disabled = true;
                // change the text back to the original
                submitBtn.innerHTML = "{{__('all.step_6_please_wait')}}";

                // submit the form
                document.getElementById('uploadForm').submit();
            }
        }

    </script>

@endsection
