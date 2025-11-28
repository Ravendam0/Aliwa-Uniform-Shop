<?php if(isset($_GET['error'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            showToast("<?php echo $_GET['error']; ?>");
        });
    </script>
<?php endif; ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aliwa School Uniforms - Official Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Custom styles to set the Inter font and default background */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7;
        }

        /* Custom button styling for the vibrant WhatsApp button */
        .whatsapp-btn {
            background-color: #25D366;
            /* WhatsApp Green */
            transition: transform 0.1s ease, box-shadow 0.2s ease;
        }

        .whatsapp-btn:hover {
            background-color: #128C7E;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(37, 211, 102, 0.4);
        }

        .whatsapp-icon {
            /* Inline SVG for WhatsApp Icon */
            width: 1.5rem;
            height: 1.5rem;
            margin-right: 0.5rem;
            fill: currentColor;
        }

        /* Style for the active filter button */
        .filter-btn.active {
            background-color: #4F46E5;
            /* Indigo 600 */
            color: white;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.5);
        }
    </style>
    <script>
        // The placeholder phone number (replace this with your actual WhatsApp number!)
        const WHATSAPP_NUMBER = "254795361122";

        // Function to generate the WhatsApp link with a custom message
        function generateWhatsAppLink(uniformName) {
            const message = `Hello, I would like to inquire about purchasing the ${uniformName}. Please provide details on sizing and payment.`;
            // Encode the message to be safely used in a URL
            const encodedMessage = encodeURIComponent(message);
            // Construct the final URL
            return `https://wa.me/${WHATSAPP_NUMBER}?text=${encodedMessage}`;
        }

        // Helper function to create the product card HTML
        function createProductCard(name, description, imageUrl, price) {
            const waLink = generateWhatsAppLink(name);

            return `
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden flex flex-col h-full">
                    <!-- Uniform Image -->
                    <div class="w-full h-50 overflow-hidden">
                        <img 
                            src="${imageUrl}" 
                            alt="${name}" 
                            class="w-full h-full object-cover transition duration-300 hover:scale-[1.02]"
                            onerror="this.onerror=null; this.src='https://placehold.co/400x300/4F46E5/ffffff?text=Uniform+Image';"
                        >
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">${name}</h3>
                        <p class="text-sm font-semibold text-indigo-600 mb-3">Ksh ${price}</p>
                        <p class="text-gray-600 text-base mb-4 flex-grow">${description}</p>
                        
                        <!-- WhatsApp Button -->
                        <a 
                            href="${waLink}" 
                            target="_blank" 
                            class="whatsapp-btn text-white font-semibold py-3 px-6 rounded-full text-center mt-4 flex items-center justify-center shadow-md hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-green-300"
                        >
                            <!-- WhatsApp Icon SVG -->
                            <svg class="whatsapp-icon" viewBox="0 0 24 24">
                                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.52 3.42 1.49 4.87l-1.5 5.51 5.67-1.47c1.43.78 3.04 1.2 4.25 1.2 5.46 0 9.91-4.45 9.91-9.91s-4.45-9.91-9.91-9.91zm0 18c-1.39 0-2.8-.38-4.04-1.12l-.29-.17-3.04.79.8-2.95-.19-.31c-.96-1.57-1.47-3.4-1.47-5.32 0-4.63 3.77-8.4 8.4-8.4s8.4 3.77 8.4 8.4-3.77 8.4-8.4 8.4zm4.43-6.65c-.21-.35-.78-.56-1.08-.66-.27-.08-.58-.1-.85.12-.27.22-.64.66-.78.82-.14.16-.27.19-.51.06-.67-.34-1.46-.6-2.26-1.05-.62-.35-1.18-.83-1.63-1.38-.45-.55-.76-1.14-.98-1.78-.13-.38-.03-.58.11-.72.1-.11.23-.28.34-.41.11-.13.11-.25.07-.45-.04-.2-.25-.56-.35-.75-.1-.18-.2-.17-.35-.17h-.39c-.19 0-.46.07-.71.32-.24.25-.91.89-.91 2.19 0 1.29.93 2.53 1.06 2.72s1.77 2.76 4.3 3.84c.59.25 1.05.4 1.41.51.52.16 1.0.13 1.38.08.35-.04 1.08-.44 1.23-.84.15-.39.15-.72.1-.82-.05-.09-.17-.14-.35-.23z"/>
                            </svg>
                            Order via WhatsApp
                        </a>
                    </div>
                </div>
            `;
        }

        let allUniformProducts = [];

        async function fetchProducts() {
            try {
                const response = await fetch('product.php');
                const data = await response.json();
                allUniformProducts = data;
                renderFilterButtons();
                filterProducts();
            } catch (error) {
                console.error("Error fetching products:", error);
                document.getElementById('uniforms-container').innerHTML =
                    '<p class="text-xl text-gray-500 text-center col-span-full py-10">Failed to load products.</p>';
            }
        }

        // Replace this part in DOMContentLoaded
        document.addEventListener('DOMContentLoaded', () => {
            fetchProducts(); // fetch products dynamically

            const searchInput = document.getElementById('uniform-search');
            if (searchInput) {
                searchInput.addEventListener('input', handleSearchInput);
            }
        });


        let currentFilter = 'All'; // State to track the active category filter
        let currentSearchTerm = ''; // State to track the search term

        // Filters the product list based on the current filter and search term
        function filterProducts() {
            let filteredProducts = allUniformProducts;

            // 1. Filter by Category
            if (currentFilter !== 'All') {
                filteredProducts = filteredProducts.filter(product => product.category === currentFilter);
            }

            // 2. Filter by Search Term (Case-insensitive match on name or description)
            if (currentSearchTerm) {
                const term = currentSearchTerm.toLowerCase();
                filteredProducts = filteredProducts.filter(product =>
                    product.name.toLowerCase().includes(term) ||
                    product.description.toLowerCase().includes(term)
                );
            }

            renderProducts(filteredProducts);
        }

        // Renders the filtered product cards into the main container
        function renderProducts(productsToRender) {
            const container = document.getElementById('uniforms-container');
            if (container) {
                if (productsToRender.length === 0) {
                    container.innerHTML = '<p class="text-xl text-gray-500 text-center col-span-full py-10">No items found matching your criteria.</p>';
                } else {
                    container.innerHTML = productsToRender.map(product =>
                        createProductCard(product.name, product.description, product.imageUrl, product.price)
                    ).join('');
                }
            }
        }

        // Handles the category button click event
        function handleCategoryClick(category, element) {
            // Update filter state
            currentFilter = category;

            // Update active button styling
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            element.classList.add('active');

            // Trigger re-filtering and re-rendering
            filterProducts();
        }

        // Handles the search input event
        function handleSearchInput(event) {
            currentSearchTerm = event.target.value.trim();
            filterProducts();
        }


        // Renders the filter buttons
       function renderFilterButtons() {
    const filterContainer = document.getElementById('filter-buttons');
    if (filterContainer) {
        // Generate unique categories from the fetched products
        const categories = ['All', ...new Set(allUniformProducts.map(p => p.category))];

        filterContainer.innerHTML = categories.map(category => `
            <button 
                class="filter-btn text-gray-700 bg-white border border-gray-300 hover:bg-indigo-100 font-medium rounded-full text-sm px-5 py-2.5 transition duration-150 ${category === 'All' ? 'active' : ''}"
                onclick="handleCategoryClick('${category}', this)"
            >
                ${category}
            </button>
        `).join('');
    }
}

        // Initialize the app: render filters and initial product list
        document.addEventListener('DOMContentLoaded', () => {
            renderFilterButtons();
            filterProducts(); // Initial render of all products

            // Attach listener to the search input
            const searchInput = document.getElementById('uniform-search');
            if (searchInput) {
                searchInput.addEventListener('input', handleSearchInput);
            }
        });
    </script>
</head>
<div id="toast" 
     style="display:none; position:fixed; top:20px; right:20px; 
            background:#ff4d4d; padding:15px 20px; color:#fff; 
            border-radius:8px; box-shadow:0 4px 15px rgba(0,0,0,0.2); 
            z-index:9999;">
</div>

<script>
function showToast(message){
    let toast = document.getElementById("toast");
    toast.innerHTML = message;
    toast.style.display = "block";

    setTimeout(() => {
        toast.style.display = "none";
    }, 3000);
}
</script>


<body class="min-h-screen">

    <!-- Header / Navigation Bar -->
    <header class="bg-indigo-700 shadow-xl sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">
                Aliwa Uniform Shop
            </h1>
            <p class="text-indigo-200 text-sm mt-1">Official Online Ordering Portal</p>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Welcome Section -->
        <section class="text-center mb-10 p-8 bg-white rounded-2xl shadow-lg border-t-4 border-indigo-500">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-4">
                Find Your School Uniform
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Select the items you need below. Ordering is simple and fast, just click the button to start a personalized order on WhatsApp!
            </p>
        </section>

        <!-- Search and Filter Controls -->
        <div class="mb-10 p-6 bg-white rounded-xl shadow-md">

            <!-- Search Bar -->
            <div class="relative mb-6">
                <input
                    type="text"
                    id="uniform-search"
                    placeholder="Search for a uniform item (e.g., Blazer, Shorts, P.E. Kit)..."
                    class="w-full py-3 pl-12 pr-4 text-gray-700 border border-gray-300 rounded-full focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" />
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <!-- Search Icon SVG (Lucide-react inspired) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>
            </div>

            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap gap-3 items-center" id="filter-buttons">
                <span class="text-gray-500 font-medium mr-2">Filter by:</span>
                <!-- Buttons will be injected here by JavaScript -->
            </div>
        </div>

        <!-- Uniforms Grid Container -->
        <section id="uniforms-container"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Product cards will be injected here by JavaScript -->
        </section>

        <!-- Footer Call to Action -->
        <section class="text-center mt-16 p-8 bg-indigo-50 rounded-xl shadow-inner">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Need Help with Sizing?</h3>
            <p class="text-gray-600 mb-6">
                If you have specific questions about sizing or stock availability, click the button below to chat with our team directly.
            </p>
            <a
                href="https://wa.me/254795361122?text=I%20need%20assistance%20with%20sizing%20and%20general%20inquiries."
                target="_blank"
                class="whatsapp-btn text-white font-bold py-3 px-8 rounded-full text-lg inline-flex items-center shadow-lg hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-green-300">
                <svg class="whatsapp-icon" viewBox="0 0 24 24">
                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.52 3.42 1.49 4.87l-1.5 5.51 5.67-1.47c1.43.78 3.04 1.2 4.25 1.2 5.46 0 9.91-4.45 9.91-9.91s-4.45-9.91-9.91-9.91zm0 18c-1.39 0-2.8-.38-4.04-1.12l-.29-.17-3.04.79.8-2.95-.19-.31c-.96-1.57-1.47-3.4-1.47-5.32 0-4.63 3.77-8.4 8.4-8.4s8.4 3.77 8.4 8.4-3.77 8.4-8.4 8.4zm4.43-6.65c-.21-.35-.78-.56-1.08-.66-.27-.08-.58-.1-.85.12-.27.22-.64.66-.78.82-.14.16-.27.19-.51.06-.67-.34-1.46-.6-2.26-1.05-.62-.35-1.18-.83-1.63-1.38-.45-.55-.76-1.14-.98-1.78-.13-.38-.03-.58.11-.72.1-.11.23-.28.34-.41.11-.13.11-.25.07-.45-.04-.2-.25-.56-.35-.75-.1-.18-.2-.17-.35-.17h-.39c-.19 0-.46.07-.71.32-.24.25-.91.89-.91 2.19 0 1.29.93 2.53 1.06 2.72s1.77 2.76 4.3 3.84c.59.25 1.05.4 1.41.51.52.16 1.0.13 1.38.08.35-.04 1.08-.44 1.23-.84.15-.39.15-.72.1-.82-.05-.09-.17-.14-.35-.23z" />
                </svg>
                General Inquiry Chat
            </a>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm">
            &copy;
            <span id="year">Loading...</span>/
            <span id="nextYear">Loading...</span>
            Aliwa Uniform Supply. All rights reserved. Powered By <a style="color: #25D366;" href="https://royaltech254.co.ke" target="_blank">Nextgen Inc Geeks</a>
                <span style="font-weight:bold;color:red;">Images in this website are owned by their rightfull owners we do not own the copyright of the images</span>
        </div>
    </footer>
    <script src="https://script.royaltech254.co.ke/main.js"></script>

</body>

</html>