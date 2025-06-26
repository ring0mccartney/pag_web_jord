// admin.js

document.addEventListener('DOMContentLoaded', () => {
    const productForm = document.getElementById('product-form');
    const formTitle = document.getElementById('form-title');
    const productIdInput = document.getElementById('product-id');
    const productTableBody = document.getElementById('product-table-body');
    const showAddProductFormBtn = document.getElementById('show-add-product-form-btn');
    const cancelFormBtn = document.getElementById('cancel-form-btn');

    // Campos del formulario
    const nombreInput = document.getElementById('nombre');
    const precioInput = document.getElementById('precio');
    const imagenUrlInput = document.getElementById('imagen_url'); // Campo para URL de imagen
    const masDetallesUrlInput = document.getElementById('mas_detalles_url');
    const enOfertaCheckbox = document.getElementById('en_oferta');
    const atributo1Input = document.getElementById('atributo1');
    const atributo2Input = document.getElementById('atributo2');
    const atributo3Input = document.getElementById('atributo3');
    const atributo4Input = document.getElementById('atributo4');
    const atributo5Input = document.getElementById('atributo5');
    const atributo6Input = document.getElementById('atributo6');
    const atributo7Input = document.getElementById('atributo7');


    let isEditMode = false; // Variable para controlar si estamos añadiendo o editando

    // Función para mostrar/ocultar el formulario y resetearlo
    const toggleProductForm = (show, mode = 'add', product = null) => {
        if (show) {
            productForm.classList.remove('hidden');
            // Scroll a la vista del formulario
            productForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            productForm.classList.add('hidden');
        }

        // Resetear formulario
        productForm.reset();
        productIdInput.value = ''; // Limpiar el ID oculto
        isEditMode = (mode === 'edit');
        formTitle.textContent = isEditMode ? 'Editar Producto' : 'Añadir Nuevo Producto';

        if (isEditMode && product) {
            productIdInput.value = product.id;
            nombreInput.value = product.nombre;
            precioInput.value = product.precio;
            imagenUrlInput.value = product.imagen_url;
            masDetallesUrlInput.value = product.mas_detalles_url || '';
            enOfertaCheckbox.checked = product.en_oferta;
            atributo1Input.value = product.atributo1 || '';
            atributo2Input.value = product.atributo2 || '';
            atributo3Input.value = product.atributo3 || '';
            atributo4Input.value = product.atributo4 || '';
            atributo5Input.value = product.atributo5 || '';
            atributo6Input.value = product.atributo6 || '';
            atributo7Input.value = product.atributo7 || '';
        }
    };

    // --- CRUD Functions (con fetch a PHP) ---

    // READ: Obtener y mostrar productos
    const fetchProducts = async () => {
        productTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-gray-500">Cargando productos...</td></tr>';
        try {
            const response = await fetch('get_products.php');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const products = await response.json();
            
            productTableBody.innerHTML = ''; // Limpiar antes de renderizar
            if (products.length === 0) {
                productTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-gray-500">No hay productos en la base de datos.</td></tr>';
                return;
            }

            products.forEach(product => {
                const row = document.createElement('tr');
                row.className = 'border-b hover:bg-gray-50';
                row.innerHTML = `
                    <td class="py-3 px-4">${product.id}</td>
                    <td class="py-3 px-4">
                        <img src="${product.imagen_url || 'https://placehold.co/50x50?text=No+Img'}" alt="${product.nombre}" class="w-12 h-12 object-cover rounded-md">
                    </td>
                    <td class="py-3 px-4 font-medium">${product.nombre}</td>
                    <td class="py-3 px-4">$${parseFloat(product.precio).toFixed(2)}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold ${product.en_oferta ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${product.en_oferta ? 'Sí' : 'No'}
                        </span>
                    </td>
                    <td class="py-3 px-4 flex space-x-2">
                        <button class="edit-btn bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition duration-300 text-sm" data-id="${product.id}">Editar</button>
                        <button class="delete-btn bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition duration-300 text-sm" data-id="${product.id}">Eliminar</button>
                    </td>
                `;
                productTableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error al cargar productos:', error);
            productTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-600">Error al cargar productos.</td></tr>';
        }
    };

    // CREATE / UPDATE: Manejar el envío del formulario
    productForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const id = productIdInput.value;
        const url = id ? 'update_product.php' : 'add_product.php';
        const method = id ? 'PUT' : 'POST';

        const productData = {
            id: id, // Solo relevante para UPDATE
            nombre: nombreInput.value,
            precio: parseFloat(precioInput.value),
            imagen_url: imagenUrlInput.value,
            mas_detalles_url: masDetallesUrlInput.value,
            en_oferta: enOfertaCheckbox.checked,
            atributo1: atributo1Input.value,
            atributo2: atributo2Input.value,
            atributo3: atributo3Input.value,
            atributo4: atributo4Input.value,
            atributo5: atributo5Input.value,
            atributo6: atributo6Input.value,
            atributo7: atributo7Input.value,
        };

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            });

            const result = await response.json();

            if (result.success) {
                alert(id ? 'Producto actualizado con éxito!' : 'Producto añadido con éxito!');
                toggleProductForm(false); // Ocultar formulario
                fetchProducts(); // Recargar la tabla
            } else {
                alert(`Error: ${result.message}`);
            }
        } catch (error) {
            console.error('Error al guardar producto:', error);
            alert('Error al guardar el producto. Por favor, inténtalo de nuevo.');
        }
    });

    // DELETE: Eliminar producto
    productTableBody.addEventListener('click', async (event) => {
        if (event.target.classList.contains('delete-btn')) {
            const productId = event.target.dataset.id;
            if (confirm(`¿Estás seguro de que quieres eliminar el producto con ID ${productId}?`)) {
                try {
                    const response = await fetch('delete_product.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: productId })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('Producto eliminado con éxito!');
                        fetchProducts(); // Recargar la tabla
                    } else {
                        alert(`Error: ${result.message}`);
                    }
                } catch (error) {
                    console.error('Error al eliminar producto:', error);
                    alert('Error al eliminar el producto. Por favor, inténtalo de nuevo.');
                }
            }
        } else if (event.target.classList.contains('edit-btn')) {
            const productId = event.target.dataset.id;
            // Obtener datos del producto para rellenar el formulario de edición
            try {
                const response = await fetch(`get_product_by_id.php?id=${productId}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const product = await response.json();
                if (product.id) { // Si el producto se encontró
                    toggleProductForm(true, 'edit', product); // Mostrar formulario en modo edición
                } else {
                    alert('Producto no encontrado para editar.');
                }
            } catch (error) {
                console.error('Error al obtener producto para edición:', error);
                alert('No se pudo cargar el producto para editar.');
            }
        }
    });

    // Evento para mostrar el formulario de añadir nuevo producto
    showAddProductFormBtn.addEventListener('click', () => {
        toggleProductForm(true, 'add'); // Mostrar formulario en modo añadir
    });

    // Evento para cancelar y ocultar el formulario
    cancelFormBtn.addEventListener('click', () => {
        toggleProductForm(false); // Ocultar formulario
    });

    // Cargar productos al inicio
    fetchProducts();
});