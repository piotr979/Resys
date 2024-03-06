(function () {
const deleteButtons = document.querySelectorAll('.delete-button');
console.log(deleteButtons);
const cancelButtons = document.querySelectorAll('.cancel-button');
const confirmButtons = document.querySelectorAll('.confirm-button');
let currentModalId = 0;
let currentEntity = '';
deleteButtons.forEach( button => {
    button.addEventListener('click', function() {
        console.log('adding event');
        currentModalId = button.getAttribute('data-id');
        currentEntity = button.getAttribute('data-entity');
        openModal('modelConfirm');
    })
})

cancelButtons.forEach( button => {
    button.addEventListener('click', function() {
        closeModal('modelConfirm');
    })
})

confirmButtons.forEach( button => {
button.addEventListener('click', function() {

    console.log('Target id is', currentModalId );
        closeModal('modelConfirm');
        location.href = `/admin/${currentEntity}/remove/${currentModalId}`;
    })
})


const openModal = (modalId) => {
    console.log('opening modal');
    document.getElementById(modalId).style.display = 'block'
    document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
}

const closeModal = (modalId) => {
    document.getElementById(modalId).style.display = 'none';
    console.log(document.getElementsByTagName('body')[0].classList);
    document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
}

// Close all modals when press ESC
document.onkeydown = function(event) {
    event = event || window.event;
    if (event.keyCode === 27) {
        document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
        let modals = document.getElementsByClassName('modal');
        Array.prototype.slice.call(modals).forEach(i => {
            i.style.display = 'none'
        })
    }
};

}());