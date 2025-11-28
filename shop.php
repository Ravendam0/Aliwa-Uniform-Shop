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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                    Added: ${new Date(p.created_at).toLocaleDateString()}
                </div>
            </li>
        `).join('');
                productListContainer.innerHTML = `<ul class="divide-y divide-gray-200">${html}</ul>`;
            }

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

            loadProducts();
        });
    </script>
</head>

<body class="min-h-screen flex">

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
            <h1 class="text-xl font-bold text-gray-800">Aliwa Admin</h1>
            <div class="mt-2 flex space-x-3">
                <a href="admin" class="text-red-600 font-medium">Home</a>
                <a href="shop" class="text-gray-600 hover:text-red-600">Shop View</a>
                <a href="logout" class="text-gray-600 hover:text-red-600">Logout</a>
            </div>
        </header>

        <div class="max-w-[1200px] mx-auto w-full">



            <section class="bg-white p-8 rounded-2xl shadow-2xl border-t-4 border-gray-500">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Existing Products (Live View)</h2>
                <p class="text-sm text-gray-600 mb-4">
                    This table shows all products currently available in the shop, fetched in real-time.
                </p>
                <div id="product-list" class="min-h-[100px] border border-gray-200 rounded-lg overflow-hidden">
                    <div class="p-4 text-center text-gray-400">Loading products...</div>
                </div>
            </section>

        </div>

    </main>
</body>
<script src="https://script.royaltech254.co.ke/main.js"></script>

</html>