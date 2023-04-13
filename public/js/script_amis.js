window.onload = () => {


    //PARTIE RECHERCHE EN AJAX

    const RechercheForm = document.querySelector('#recherche');

    const Input = document.querySelector('#recherche input');

    Input.addEventListener('input', () => {
        if(Input.value){
        // ici on intercepte quand l'utilisateur écrit
        // On récupère les données du formulaire
        const Form = new FormData(RechercheForm);

        //On fabrique la "queryString" (paramètre de l'url)
        const Params = new URLSearchParams();

        Form.forEach((value, key) => {
            Params.append(key, value);
        });

        // On récupère l'url active
        const Url = new URL(window.location.href);

        // On lance la requête AJAX
        fetch(Url.pathname + "?" + Params.toString() + "&ajax=recherche", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(response => 
            response.json()
        ).then(data => {
            // On vient chercher la zone de contenu
            const content = document.querySelector('#ajoutsAmis');
            // On remplace le contenu
            content.innerHTML = data.content;
            //On associe les boutons .btn_ajouter a la méthode ajouter
            document.querySelectorAll('.btn_ajouter').forEach(btn => {
                btn.addEventListener('click', ajoutAmis);
            })
        })
        .catch(e => alert(e));
    }

    if(!Input.value){
        const content = document.querySelector('#ajoutsAmis');
        content.innerHTML = '';
    }});

    //PARTIE AJOUT D'AMI EN AJAX

    function ajoutAmis(){
        //On récupère l'url /contacts
        const Url = new URL(window.location.href);

        fetch(Url.pathname + '?pseudo=' + this.parentNode.id + '&ajax=ajoutami', {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then( response => 
            response.json()
        ).then(data => {
            this.parentNode.remove();
            const content = document.querySelector('#listeAmis');
            content.innerHTML = data.content;
            document.querySelectorAll('.btn_supprimer').forEach(btn => {
                btn.addEventListener('click', suppressionAmi);
            })
        })
        .catch(e => alert(e))
    }

    //PARTIE SUPPRESSION D'AMI EN AJAX

    function suppressionAmi(){
        const Url = new URL(window.location.href);

        console.log('suppression');

        fetch(Url.pathname + '?pseudo=' + this.parentNode.id + '&ajax=suppressionami', {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(
            this.parentNode.remove()
        )
        .catch(e => alert(e))

        console.log('ami supprimé');
    }

    //EVENTLISTENER SUR BTN_SUPPRESSION
    document.querySelectorAll('.btn_supprimer').forEach(btn => {
        btn.addEventListener('click', suppressionAmi);
    })
}