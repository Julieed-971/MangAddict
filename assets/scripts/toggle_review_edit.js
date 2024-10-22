// public/js/script.js

function toggleEdit() {
event.preventDefault();

    const contentDiv = document.querySelector('.review__current');
    const editDiv = document.querySelector('.review__current-edit');
    
    contentDiv.style.display = contentDiv.style.display === 'none' ? 'block' : 'none';
    editDiv.style.display = editDiv.style.display === 'none' ? 'block' : 'none';
}

function deleteReview() {
    const deleteForm = document.getElementById('delete-review-form');
    deleteForm.submit();
}
function deleteRating() {
    const deleteForm = document.getElementById('delete-rating-form');
    deleteForm.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const modifyButton = document.querySelector('.review__button-modify');
    if (modifyButton) {
        modifyButton.addEventListener('click', toggleEdit);
    }

    const cancelButton = document.querySelector('.review__content-edit-cancel');
    if (cancelButton) {
        cancelButton.addEventListener('click', toggleEdit);
    }

	const deleteReviewButton = document.querySelector('.review__button-delete');
    if (deleteReviewButton) {
        deleteReviewButton.addEventListener('click', deleteReview);
    }
    const deleteRatingButton = document.querySelector('.rating__button-delete');
    if (deleteRatingButton) {
        deleteRatingButton.addEventListener('click', deleteRating);
    }
});