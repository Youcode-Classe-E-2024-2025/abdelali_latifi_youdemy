     const openModalButton = document.getElementById('openModalButton');
     const closeModalButton = document.getElementById('closeModalButton');
     const courseModal = document.getElementById('courseModal');

     openModalButton.addEventListener('click', () => {
         courseModal.classList.remove('hidden');
     });

     closeModalButton.addEventListener('click', () => {
         courseModal.classList.add('hidden');
     });

     window.addEventListener('click', (event) => {
         if (event.target === courseModal) {
             courseModal.classList.add('hidden');
         }
     });