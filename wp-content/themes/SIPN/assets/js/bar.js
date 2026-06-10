document.addEventListener('DOMContentLoaded', function () {
    let deletedBottles = [];
    let editedFields = [];

    // When the 'Edit' button is clicked
    document.querySelector('.bar-edit').addEventListener('click', function () {
        const isEditing = this.classList.contains('editing');


        if (isEditing) {
            // User clicks 'Save' button
            this.textContent = 'Edit';
            this.classList.remove('editing');

            // Disable drag-and-drop
            document.querySelectorAll('.carousel').forEach(carousel => {
                Sortable.get(carousel).option("disabled", true);
            });

            // Make input fields read-only again
            document.querySelectorAll('.shelfedit').forEach(input => {
                input.readOnly = true;
                input.style.border = 'none';
            });

            // Hide delete buttons
            document.querySelectorAll('.delete-bottle').forEach(btn => {
                btn.style.display = 'none';
            });

            // **Trigger the save functionality (e.g., by clicking a 'Save' button)**

            submitChanges(deletedBottles, editedFields);
            

        } else {
            // Enter edit mode
            this.textContent = 'Save';
            this.classList.add('editing');

            // Enable drag-and-drop
            document.querySelectorAll('.carousel').forEach(carousel => {
                Sortable.get(carousel).option("disabled", false);
            });

            // Make input fields editable
            document.querySelectorAll('.shelfedit').forEach(input => {
                input.readOnly = false;
                input.style.border = '1px solid #ccc';
            });

            // Show delete buttons
            document.querySelectorAll('.delete-bottle').forEach(btn => {
                btn.style.display = 'block';

                // Add click event for each delete button
                btn.addEventListener('click', function () {
                    const bottle = this.closest('.bottle');
                    const shelfId = bottle.closest('.carousel').getAttribute('shelf_id');
                    const dataId = bottle.getAttribute('data-id');
                    console.log("Shelf ID : "+shelfId)
                    console.log("data ID : "+dataId)
                    // Add the deleted bottle details to the array
                    deletedBottles.push({ shelf_id: shelfId, weight: dataId });

                    // Remove the bottle from the DOM immediately
                    bottle.remove();
                    addDeletedBottle(shelfId, dataId);
                });
            });

            // Add event listener for input field changes
            document.querySelectorAll('.shelfedit').forEach(input => {
                input.addEventListener('input', function () {
                    const ssid = this.getAttribute('ssid');
                    const barName = this.value;
                    const existingEntryIndex = editedFields.findIndex(field => field.shelf_id === ssid);

                    if (existingEntryIndex > -1) {
                        editedFields[existingEntryIndex].shelf_name = barName;
                    } else {
                        editedFields.push({ shelf_id: ssid, shelf_name: barName });
                    }

                });
            });

        }
    });

    function addDeletedBottle(shelfId, bottleId) {
        const defaultBottle = document.createElement('div');
        defaultBottle.classList.add('bottle', 'non-draggable');
        defaultBottle.setAttribute('data-id', bottleId);
    
        // Find the correct carousel using the shelfId
        const carousel = document.querySelector(`.carousel[shelf_id="${shelfId}"]`);
    
        if (carousel) {
            // Get the list of existing bottles in the carousel
            const bottles = Array.from(carousel.querySelectorAll('.bottle'));
    
            // Find the position where the bottle was deleted
            let insertAfter = null;
            bottles.forEach(bottle => {
                if (bottle.getAttribute('data-id') === bottleId) {
                    insertAfter = bottle;
                }
            });
    
            // Create the anchor tag
            const anchor = document.createElement('a');
            anchor.href = `/?s=&si=${shelfId}&w=${bottleId}`; // Use shelfId and bottleId in the URL
    
            // Bottle image
            const img = document.createElement('img');
            img.src = 'https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icons/default.png';
            img.alt = `Bottle ${bottleId}`;
    
            // Append the image to the anchor
            anchor.appendChild(img);
    
            // Delete button span
            const deleteButton = document.createElement('span');
            deleteButton.classList.add('delete-bottle');
            deleteButton.innerHTML = '...'; // Customize the content of the delete button here
            deleteButton.style.display = 'block';
    
            // Bottle title div
            const bottleTitle = document.createElement('div');
            bottleTitle.classList.add('bottle-title');
            bottleTitle.innerHTML = '...'; // Customize the title here
    
            // Append the anchor, delete button, and bottle title to the default bottle
            defaultBottle.appendChild(anchor);
            defaultBottle.appendChild(deleteButton);
            defaultBottle.appendChild(bottleTitle);
    
            // Insert the default bottle at the correct position
            if (insertAfter) {
                carousel.insertBefore(defaultBottle, insertAfter.nextSibling);
            } else {
                // If the bottleId is not found, append at the end (fallback)
                carousel.appendChild(defaultBottle);
            }
    
            // Sort bottles based on data-id
            const sortedBottles = Array.from(carousel.querySelectorAll('.bottle'))
                .sort((a, b) => {
                    return parseInt(a.getAttribute('data-id')) - parseInt(b.getAttribute('data-id'));
                });
    
            // Clear the carousel and append sorted bottles
            carousel.innerHTML = '';
            sortedBottles.forEach(bottle => {
                carousel.appendChild(bottle);
            });
        } else {
            console.error(`Carousel with shelf_id "${shelfId}" not found.`);
        }
    }

    function submitChanges(deletedBottles, editedFields) {
        const barId = document.querySelector('.shelf-container').getAttribute('bar_id');
        const dataToSend = {
            bar_id: barId,
            deleted_bottles: deletedBottles,
            shelfnames: editedFields
        };
    
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: site_script_object.ajaxurl,
            data: {
                'action': 'ajaxsavebarchanges',
                'payload': JSON.stringify(dataToSend),
                'nonce': site_script_object.nonce,
            },
            success: function (data) {
             
                let message = '';
    
                if (data.deleted_bottles && data.deleted_bottles.status) {
                    message += data.deleted_bottles.message + ' ';
                } else if (data.deleted_bottles && !data.deleted_bottles.status) {
                    message += data.deleted_bottles.message + ' ';
                }
    
                if (data.shelfnames && data.shelfnames.status) {
                    message += data.shelfnames.message + ' ';
                } else if (data.shelfnames && !data.shelfnames.status) {
                    message += data.shelfnames.message + ' ';
                }
    
               
                // $('#ajax-success-message').text(message.trim()).fadeIn();
    
                setTimeout(function () {
                    $('#ajax-success-message').fadeOut(function () {
                        location.reload(); 
                    });
                }, 1000);
    
                deletedBottles = [];
                editedFields = {};
            },
            error: function (xhr, status, error) {
                console.error('Error saving changes:', error);
                
                $('#ajax-success-message').text('An error occurred while saving changes.').fadeIn();
                setTimeout(function () {
                    $('#ajax-success-message').fadeOut();
                }, 3000);
            }
        });
    }


    
});







/* Drag and drop functionality */

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.carousel').forEach(carousel => {
        const shelfContainer = carousel.closest('.shelf-container');
        Sortable.get(carousel).option("disabled", true);
        if (shelfContainer.classList.contains('show-default')) {
            manageBottles(carousel);
        }
    });
});

function manageBottles(carousel) {
    const maxBottles = 15;
    const bottles = carousel.querySelectorAll('.bottle');
    let nonDefaultBottlesCount = 0;
    let lastBottleIsNonDefault = false;
    let hasDefaultBottle = false;

    // Count non-default bottles, check if the last bottle is non-default,
    // and check if there's already a default bottle
    bottles.forEach(bottle => {
        const img = bottle.querySelector('img');
        if (img) {
            if (!img.src.includes('default.png')) {
                nonDefaultBottlesCount++;
                lastBottleIsNonDefault = true;
            } else {
                lastBottleIsNonDefault = false;
                hasDefaultBottle = true;
            }
        }
    });

    // Ensure we only add a default bottle if the shelf has less than 15 bottles,
    // the last bottle is non-default, and there is no existing default bottle
    if (bottles.length < maxBottles && lastBottleIsNonDefault && !hasDefaultBottle) {
        addDefaultBottle(carousel, bottles.length + 1);
    }

    // Remove any extra bottles if the total number exceeds 15
    if (bottles.length > maxBottles) {
        for (let i = maxBottles; i < bottles.length; i++) {
            bottles[i].remove();
        }
    }
}





document.querySelectorAll('.carousel').forEach(carousel => {
    new Sortable(carousel, {
        group: 'shared',
        animation: 150,
        scroll: true,
        filter: '.non-draggable',
        onStart: function (evt) {
            const item = evt.item;
            if (!item.querySelector('img').src.includes('default.png')) {
                // Remove 'non-draggable' class from all bottles with default.png images
                document.querySelectorAll('.carousel .bottle').forEach(bottle => {
                    const img = bottle.querySelector('img');
                    if (img && img.src.includes('default.png')) {
                        bottle.classList.remove('non-draggable');
                    }
                });
            }
            // Store the original data-id of the dragged item and the reference to its previous sibling
            evt.item.dataset.originalId = evt.item.getAttribute('data-id');
            evt.item.dataset.originalPrevId = evt.item.previousElementSibling
                ? evt.item.previousElementSibling.getAttribute('data-id')
                : null;
        },
        onEnd: function (evt) {
            const fromCarousel = evt.from;
            const toCarousel = evt.to;
            const draggedItem = evt.item;
            const draggedItemPid = draggedItem.getAttribute('pid');
            const oldShelfId = fromCarousel.getAttribute('shelf_id');
            const newShelfId = toCarousel.getAttribute('shelf_id');

            const count = countNonDefaultBottles(toCarousel);
            // Check if the target shelf already has 15 bottles
            if (toCarousel && count > 15) {
                alert('This shelf already has the maximum number of bottles (15).');

                // Retrieve the original data-id of the dragged item
                const originalId = draggedItem.dataset.originalId;
                const originalPrevId = draggedItem.dataset.originalPrevId;

                // Find the reference element for the original position
                const originalPrevElement = originalPrevId
                    ? fromCarousel.querySelector(`[data-id="${originalPrevId}"]`)
                    : null;

                // Move the item back to its original position in the original carousel
                if (originalPrevElement) {
                    fromCarousel.insertBefore(draggedItem, originalPrevElement.nextElementSibling);
                } else {
                    fromCarousel.insertBefore(draggedItem, fromCarousel.firstChild);
                }

                // Optionally update IDs after reverting the item
                updateDataIds(fromCarousel);
                return; // Prevent further processing
            }


            // Handle swapping and positioning logic
            if (evt.related && evt.related.classList.contains('non-draggable')) {
                if (!draggedItem.classList.contains('non-draggable')) {
                    toCarousel.insertBefore(draggedItem, evt.related);
                    fromCarousel.insertBefore(evt.related, draggedItem.nextSibling);
                }
            }

            const nextBottle = draggedItem.nextElementSibling;

            if (nextBottle) {
                const img = nextBottle.querySelector('img');
                console.log('default image:' + img.src.includes('default.png'));
                if (img && img.src.includes('default.png')) {
                    // Remove the bottle with the default.png image
                    if (toCarousel !== fromCarousel) {
                        nextBottle.remove();
                    }
                }
            }


            // Your existing functions to handle blank bottles, filling empty spots, and updating data-ids
            removeBlankBottles(toCarousel);
            checkAndFillEmptySpots(fromCarousel);
            //checkAndFillEmptySpots(toCarousel);
            updateDataIds(fromCarousel);
            updateDataIds(toCarousel);

            // Reset scale transformation on dragged item
            const active = document.querySelector('.sortable-chosen');
            if (active) {
                active.style.transform = 'scale(1)';
            }
            manageBottles(toCarousel);

            // After all operations, add 'non-draggable' class to all bottles with default.png images
            document.querySelectorAll('.carousel .bottle').forEach(bottle => {
                const img = bottle.querySelector('img');
                if (img && img.src.includes('default.png')) {
                    bottle.classList.add('non-draggable');
                }
            });

            const barId = document.querySelector('.shelf-container').getAttribute('bar_id');

            // Build the product data for the new shelf
            const productData = Array.from(toCarousel.querySelectorAll('.bottle')).map((bottle, index) => {
                const pid = bottle.getAttribute('pid');
                return {
                    new_shelve_id: newShelfId,
                    old_shelve_id: pid === draggedItemPid ? oldShelfId : newShelfId, // Only the dragged item gets a different old_shelve_id
                    order: index + 1,
                    pid: pid
                };
            });

            // Create the final data structure
            const dataToSend = {
                bar_id: barId,
                product: productData,
                shelve_id: newShelfId
            };

            // Trigger AJAX call
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: site_script_object.ajaxurl,
                data: {
                    'action': 'ajaxproductsreordercrossshelf',
                    'payload': JSON.stringify(dataToSend), // Sending the structured data
                    'nonce': site_script_object.nonce,
                },
                success: function (data) {
                    console.log('Product reordered successfully:', data);
                },
                error: function (xhr, status, error) {
                    console.error('Error reordering product:', error);
                }
            });

        },
        onMove: function (evt) {

            // Prevent dragging non-draggable items
            if (evt.related && evt.related.classList.contains('non-draggable')) {
                return evt.dragged && !evt.dragged.classList.contains('non-draggable');
            }

            // Prevent dragging non-draggable items
            if (evt.dragged.classList.contains('non-draggable')) {
                return false;
            }

        }
    });
});

function countNonDefaultBottles(carousel) {
    const bottles = carousel.querySelectorAll('.bottle');
    let count = 0;

    bottles.forEach(bottle => {
        const img = bottle.querySelector('img');
        if (img && !img.src.includes('default.png')) {
            console.log("current_count"+count);
            count++;
        }
    });

    return count;
}



function removeBlankBottles(carousel) {
    const blankBottles = carousel.querySelectorAll('.bottle .non-draggable');
    blankBottles.forEach(blankBottle => {
        carousel.removeChild(blankBottle);
    });
}

function checkAndFillEmptySpots(carousel) {
    const maxBottles = 15;
    const minBottles = 3;
    const bottles = carousel.querySelectorAll('.bottle');
    const currentBottlesCount = bottles.length;

    // Ensure at least 3 bottles
    if (currentBottlesCount < minBottles) {
        for (let i = currentBottlesCount; i < minBottles; i++) {
            addDefaultBottle(carousel, i + 1);
        }
    } else if (currentBottlesCount > minBottles) {
        let lastNonDefaultBottleIndex = -1;
        let lastBottleIsNonDefault = false;

        // Determine the index of the last non-default bottle
        bottles.forEach((bottle, index) => {
            const img = bottle.querySelector('img');
            if (img && !img.src.includes('default.png')) {
                lastNonDefaultBottleIndex = index;
                lastBottleIsNonDefault = true;
            }
        });

        // Remove any existing default bottles after the last non-default bottle
        if (lastBottleIsNonDefault) {
            for (let i = lastNonDefaultBottleIndex + 1; i < bottles.length; i++) {
                const bottle = bottles[i];
                if (bottle.querySelector('img').src.includes('default.png')) {
                    carousel.removeChild(bottle);
                }
            }

            // Add one default bottle if needed
            if (currentBottlesCount < maxBottles) {
                const existingDefaultBottlesCount = carousel.querySelectorAll('.bottle.non-draggable').length;
                if (existingDefaultBottlesCount === 0) {
                    addDefaultBottle(carousel, currentBottlesCount + 1);
                }
            }
        }
    }
}
function addDefaultBottle(carousel, bottleId) {
    const defaultBottle = document.createElement('div');
    defaultBottle.classList.add('bottle', 'non-draggable');
    defaultBottle.setAttribute('data-id', bottleId);

    // Get the shelf_id from the parent element (carousel)
    const shelfId = carousel.getAttribute('data-shelf-id') || '';

    // Create the anchor tag
    const anchor = document.createElement('a');
    anchor.href = `/?s=&si=${shelfId}&w=${bottleId}`; // Use shelfId and bottleId in the URL

    // Bottle image
    const img = document.createElement('img');
    img.src = 'https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icons/default.png';
    img.alt = `Bottle ${bottleId}`;

    // Append the image to the anchor
    anchor.appendChild(img);

    // Delete button span
    const deleteButton = document.createElement('span');
    deleteButton.classList.add('delete-bottle');
    deleteButton.innerHTML = '...'; // Customize the content of the delete button here
    deleteButton.style.display = 'block';

    // Bottle title div
    const bottleTitle = document.createElement('div');
    bottleTitle.classList.add('bottle-title');
    bottleTitle.innerHTML = '...'; // Customize the title here

    // Append the anchor to the default bottle
    defaultBottle.appendChild(anchor);
    defaultBottle.appendChild(deleteButton);
    defaultBottle.appendChild(bottleTitle);

    // Append the default bottle to the carousel
    carousel.appendChild(defaultBottle);
}




function updateDataIds(carousel) {
    const bottles = carousel.querySelectorAll('.bottle');
    bottles.forEach((bottle, index) => {
        bottle.setAttribute('data-id', index + 1);
    });
}

function scrollCarousel(button, direction) {
    const shelf = button.closest('.shelf');
    const carousel = shelf.querySelector('.carousel');
    if (!carousel) return;

    const bottle = carousel.querySelector('.bottle');
    const bottleWidth = bottle ? bottle.offsetWidth : 0;
    const margin = 20;
    const scrollAmount = bottleWidth + margin;

    // Get current scroll position and maximum scroll position
    const currentScroll = carousel.scrollLeft;
    const maxScroll = carousel.scrollWidth - carousel.clientWidth;

    if (direction === 'left') {
        const newScrollPosition = Math.max(currentScroll - scrollAmount, 0);
        carousel.scrollTo({
            left: newScrollPosition,
            behavior: 'smooth'
        });
    } else if (direction === 'right') {
        const newScrollPosition = Math.min(currentScroll + scrollAmount, maxScroll);
        carousel.scrollTo({
            left: newScrollPosition,
            behavior: 'smooth'
        });
    }
}
