window.onload = () => {

    const MessageForm = document.querySelector('#message');
    const Input = document.querySelector('#message input');
    const BoutonEnvoyer = document.querySelector('#envoyer');

    BoutonEnvoyer.addEventListener('click', ajoutMessage);
    Input.addEventListener('keyup', verifEntree)


    function verifEntree (e){
        if(e.key == "Enter"){
            ajoutMessage();
        }
    }

    function ajoutMessage(){
        if(Input.value){
            const Form = new FormData(MessageForm);
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                Params.append(key, value);
            })

            const Url = new URL(window.location.href);

            fetch(Url.pathname + "?" + Params.toString() + '&ajax=envoi', {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(reponse => 
                reponse.json()
            ).then(data => {
                const Discussion = document.querySelector('#discussion');
                Discussion.innerHTML = data.content;
                Input.value = "";
            }).catch(e => alert(e));
        }
    }

    function refreshMessages(){
        const Url = new URL(window.location.href);

        fetch(Url.pathname + "?" + '&ajax=refresh', {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(reponse => 
            reponse.json()
        ).then(data => {
            const Discussion = document.querySelector('#discussion');
            Discussion.innerHTML = data.content;
        }).catch(e => alert(e));
        setTimeout(refreshMessages,2000);
    }

    refreshMessages();
}