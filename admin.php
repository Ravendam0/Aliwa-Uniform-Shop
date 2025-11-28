<?php
include("codes/protect.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aliwa Uniform Shop - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
            /* Light gray background */
        }

        /* Mobile: Sidebar is hidden, Content uses full width */
        .content-area {
            width: 100%;
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }

        /* Desktop/Tablet: Sidebar visible, Content shifts */
        @media (min-width: 768px) {
            .sidebar {
                width: 280px;
                display: flex;
            }

            .content-area {
                margin-left: 280px;
                width: calc(100% - 280px);
            }
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .product-list-item:hover {
            background-color: #f9fafb;
        }
    </style>

    <script type="module">
        const statusDiv = document.getElementById('admin-status');
        const progressBarContainer = document.getElementById('progress-container');
        const progressBar = document.getElementById('progress-bar');
        const submitButton = document.getElementById('submit-btn');
        const productListContainer = document.getElementById('product-list');

        function updateStatus(message, type = 'info') {
            if (!statusDiv) return;
            statusDiv.classList.remove('hidden', 'bg-red-100', 'text-red-800', 'bg-yellow-100', 'text-yellow-800', 'bg-green-100', 'text-green-800');
            statusDiv.textContent = message;
            if (type === 'error') statusDiv.classList.add('bg-red-100', 'text-red-800');
            else if (type === 'success') statusDiv.classList.add('bg-green-100', 'text-green-800');
            else statusDiv.classList.add('bg-yellow-100', 'text-yellow-800');
            statusDiv.classList.remove('hidden');
        }

        function updateProgress(percentage) {
            if (!progressBarContainer || !progressBar) return;
            if (percentage > 0 && percentage <= 100) {
                progressBarContainer.classList.remove('hidden');
                progressBar.style.width = percentage + '%';
            } else {
                progressBarContainer.classList.add('hidden');
            }
        }

        function renderProductList(products) {
            if (!productListContainer) return;
            if (products.length === 0) {
                productListContainer.innerHTML = '<p class="text-gray-500 p-4">No products added yet.</p>';
                return;
            }
            const html = products.map(p => `
            <li class="product-list-item flex items-center p-3 border-b border-gray-100 transition duration-100">
                <img src="${p.imageUrl || 'https://placehold.co/60x60/CCCCCC/666666?text=No+Img'}" alt="${p.name}" class="w-12 h-12 object-cover rounded-md mr-4 shadow-sm">
                <div class="flex-grow min-w-0">
                    <p class="text-base font-semibold text-gray-800 truncate">${p.name}</p>
                    <p class="text-sm text-gray-500">${p.category} - Ksh ${p.price}</p>
                </div>
                <div class="ml-4 text-xs text-gray-400 text-right hidden sm:block">
                    Added: ${new Date(p.createdAt).toLocaleDateString()}
                </div>
            </li>
        `).join('');
            productListContainer.innerHTML = `<ul class="divide-y divide-gray-200">${html}</ul>`;
        }

        window.submitProduct = function(event) {
            event.preventDefault(); // ✅ Prevent form from navigating

            const form = event.target;
            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';
            updateProgress(0);
            updateStatus('Starting upload...', 'info');

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    updateProgress(percent);
                    updateStatus(`Upload progress: ${percent}%`, 'info');
                }
            });

            xhr.addEventListener('load', () => {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showToast('Product uploaded successfully!', 'success');
                        form.reset();
                        loadProducts();
                    } else {
                        updateStatus(response.error, 'error');
                    }
                } catch (err) {
                    console.error(err);
                    updateStatus('Unexpected response from server.', 'error');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Add Product to Store';
                    setTimeout(() => updateProgress(0), 500);
                }
            });

            xhr.addEventListener('error', () => {
                updateStatus('Upload failed.', 'error');
                submitButton.disabled = false;
                submitButton.textContent = 'Add Product to Store';
            });

            xhr.open('POST', 'upload.php'); // Send via AJAX
            xhr.send(formData);
        };


        async function loadProducts() {
            updateStatus('Loading products...', 'info');
            try {
                const res = await fetch('product.php');
                const products = await res.json();
                renderProductList(products);
                updateStatus('Products loaded.', 'success');
            } catch (err) {
                console.error(err);
                updateStatus('Failed to fetch products.', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            submitButton.disabled = false;
            loadProducts();
        });
    </script>
    <script>
        function showToast(message, type = 'success', duration = 3000) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `
        px-4 py-3 rounded-lg shadow-lg text-white font-semibold transition-all
        ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-yellow-500'}
        opacity-0 translate-y-2
    `;
            toast.textContent = message;

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
                toast.classList.add('opacity-100', 'translate-y-0');
            });

            // Remove after duration
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                toast.addEventListener('transitionend', () => toast.remove());
            }, duration);
        }
    </script>

</head>

<body class="min-h-screen flex">
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-5 right-5 space-y-2 z-50"></div>


    <!-- Sidebar Navigation (Fixed on Desktop) -->
    <nav class="sidebar fixed top-0 left-0 h-screen bg-red-800 text-white p-6 shadow-2xl z-20 
                hidden md:flex flex-col justify-between">

        <!-- Top Section: Logo and Links -->
        <div>
            <div class="text-2xl font-extrabold mb-8 border-b border-red-700 pb-3">
                <span class="text-red-300">Aliwa</span> Admin
            </div>

            <a href="admin"
                class="flex items-center p-3 rounded-lg bg-red-700 hover:bg-red-600 font-semibold mb-2 transition duration-150">
                <!-- Home Icon SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Home (Admin Panel)
            </a>

            <a href="shop"
                class="flex items-center p-3 rounded-lg hover:bg-red-700 transition duration-150">
                <!-- Shop Icon SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                Shop View
            </a>
        </div>

        <!-- Bottom Section: Logout -->
        <a href="logout"
            class="flex items-center p-3 rounded-lg bg-red-600 hover:bg-red-700 font-semibold transition duration-150 mt-4">
            <!-- Logout Icon SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-3">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
            Logout
        </a>
    </nav>


    <!-- Main Content Area -->
    <main class="content-area min-h-screen py-6 px-4 md:py-12 md:px-8">

        <!-- Header (Only visible on mobile) -->
        <header class="bg-white shadow-md p-4 mb-6 md:hidden rounded-lg">
            <h1 class="text-xl font-bold text-gray-800">ALiwa Admin</h1>
            <div class="mt-2 flex space-x-3">
                <a href="admin" class="text-red-600 font-medium">Home</a>
                <a href="shop" class="text-gray-600 hover:text-red-600">Shop View</a>
                <a href="logout" class="text-gray-600 hover:text-red-600">Logout</a>
            </div>
        </header>

        <div class="max-w-[1200px] mx-auto w-full">

            <!-- Status and Progress Area -->
            <div id="admin-status" class="p-4 mb-4 rounded-lg text-sm bg-yellow-100 text-yellow-800 hidden">
                Initializing database connection...
            </div>

            <div id="progress-container" class="w-full bg-gray-200 rounded-full h-2.5 mb-6 hidden">
                <div id="progress-bar" class="bg-red-600 h-2.5 rounded-full progress-bar" style="width: 0%"></div>
            </div>

            <section class="bg-white p-8 rounded-2xl shadow-2xl border-t-4 border-red-500 mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Uptime</h2>
                <p style="font-size: 20px;font-weight:bold;" id="timeActive">Loading...</p>
            </section>
            <section class="bg-white p-8 rounded-2xl shadow-2xl border-t-4 border-red-500 mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Add New Uniform Item</h2>
                <form id="admin-form" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="submitProduct(event)">


                    <div>
                        <label for="productName" class="block text-sm font-medium text-gray-700 mb-1">Uniform Name</label>
                        <input type="text" id="productName" name="productName" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-red-500 focus:border-red-500">
                    </div>

                    <div>
                        <label for="productDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="productDescription" name="productDescription" rows="3" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-red-500 focus:border-red-500"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Price -->
                        <div>
                            <label for="productPrice" class="block text-sm font-medium text-gray-700 mb-1">Price (Ksh)</label>
                            <input type="number" id="productPrice" name="productPrice" required
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <!-- Category -->
                        <div class="md:col-span-2">
                            <label for="productCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="productCategory" name="productCategory" required
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-red-500 focus:border-red-500">
                                <option value="">-- Select Category --</option>
                                <option value="Primary">Primary</option>
                                <option value="High School">High School</option>
                                <option value="Pre-School">Pre-School</option>
                                <option value="P.E. / Sports">P.E. / Sports</option>
                                <option value="Accessories">Accessories</option>
                                <option value="All Students">All Students</option>
                            </select>
                        </div>
                    </div>

                    <!-- Image File Upload -->
                    <div>
                        <label for="productImage" class="block text-sm font-medium text-gray-700 mb-1">Uniform Image Upload</label>
                        <input type="file" id="productImage" name="productImage" accept="image/*" required
                            class="w-full p-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500">Select an image file (PNG, JPG) to upload.</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-btn" disabled
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        Add Product to Store
                    </button>
                </form>
            </section>

        </div>

    </main>
</body>
<script src="https://script.royaltech254.co.ke/main.js"></script>
<script>
    // Platform-specific start time
    startActiveTimer('2025-11-28T22:11:00+03:00', 'timeActive');
</script>

</html>