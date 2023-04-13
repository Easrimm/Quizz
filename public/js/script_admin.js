window.onload = () => {

    const BtnBannir = document.querySelector('#btn_bannir');
    BtnBannir.addEventListener('click', () => {
        document.querySelector('#btn_bannir .admin__ban__search').classList.remove('hidden');
    })

    //AJAX RECHERCHE PAR pseudo

    const InputBan = document.querySelector('.admin__ban__search__input');

    InputBan.addEventListener('input', () => {
        if(InputBan.value){
            const Url = new URL(window.location.href);
            fetch(Url.pathname + '?pseudo=' + InputBan.value + '&ajax=ban', {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response => 
                response.json()
            ).then(data => {
                const content = document.querySelector('.admin__ban__search__list');
                content.innerHTML = data.content;
            })
        }
        if(!InputBan.value){
            const content = document.querySelector('.admin__ban__search__list');
            content.innerHTML = '';
        }
    })
}