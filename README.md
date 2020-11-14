# **Projet Peer to Peer**
> Projet basé sur une interface de programmation JavaScript développée au sein du [W3C](https://www.w3.org/) et de l'[IETF](https://www.ietf.org/).

# 1. Utilisation

Plutot que de vous faire installer le projet sur votre machine nous avons hébergé l'application sur un serveur heroku. L'api web s'occupe des échanges préalables à la communication entre les deux utilisateurs en liant deux "demandes".
- L'une est une demande d'initiation de conversation
- La seconde une demande de reception

Nous avons lié cette API à une base de données mysql (= simple au vu du besoin) pour pouvoir stocker une initiation de demande. Le but étant de piloter l'échange sans que l'utilisateur ai besoin de communiquer des données sensibles lui même via des moyens de communication à l'éthique reprochable voir douteuse. 

| id 	| conversation_name 	|      initiation_json     	|       reception_json      	|
|:--:	|:-----------------:	|:------------------------:	|:-------------------------:	|
| 1  	| test1             	| {"type":"offer","s.....} 	| null 	|
| 2  	| withoutsound            	| {"type":"offer","k.....} 	| {"type":"answer","s.....} 	|
| 3  	| withsound          	| {"type":"offer","h.....} 	| {"type":"answer","s.....} 	|

<hr>

Nous avons chercher à rendre plus *userfriendly* l'interface. Ainsi il suffit de préparer un nom de conversation sur la partie gauche et de   
- <span style="color:green;border:0.5px solid;border-radius:3px;padding:0px 3px">Démarer la conversation</span>
<center>
<details>
    <summary>Spoiler</summary>
    <span style="color:grey">la gauche c'est la main ou le pouce est à droite.</span>
</details>  
</center>

Pour la "récéption" il suffit de saisir le nom de la conversation sur la partie droite et de cliquer sur 
- <span style="color:dodgerblue;border:0.5px solid;border-radius:3px;padding:0px 3px">Recevoir la conversation</span>

Notre API nous permet donc de nous occuper coté back-end de gérer discretement l'échange du json qui serait bien trop long pour la plupart des moyen de communication (~9000 charactères). Nous avons pris le parti de le laisser sur la page pour laisser une trace de ce a quoi correspond les données nécéssaires à la librairie javascript pour lier deux machines. (PS : Il y a du [<span style="text-decoration:underline">fingerprinting</span>](https://en.wikipedia.org/wiki/Device_fingerprint) et même l'adresse IP locale).




# 2. Fonctionnement

## 2.1 Principe
Le WebRTC (Web Real-Time Communication) repose sur une architecture triangulaire puis pair à pair dans laquelle un serveur central est utilisé pour mettre en relation deux pairs désirant échanger des flux de médias ou de données qui échangent ensuite sans autre relais.
<center> 

![Graphique associé aux étapes](https://upload.wikimedia.org/wikipedia/commons/thumb/9/97/Webrtc_triangle_architecture.svg/330px-Webrtc_triangle_architecture.svg.png)

</center>
<hr>

<center> 

**Construction triangulaire impliquant un serveur et deux pairs.**

![Graphique associé aux étapes](https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Etablissement_d%27une_connexion_par_WebRTC.svg/330px-Etablissement_d%27une_connexion_par_WebRTC.svg.png)

    1 : A demande au serveur une connexion avec B.
    2 : Le serveur relaie la demande de A à B.
    3 : Si B accepte, il envoie une demande de connexion à A.
    4 : Le serveur relaie la demande à A.
    5 et 6 : Les PeerConnection bidirectionnelles sont établies.
</center>

## 2.2 Le paramétrage JS


<details>
    <summary>La partie ou l'on a configuré les flux</summary>  

```JS
navigator.getUserMedia({
            video: true,
            audio: true
        }, function(stream){
            let p = new SimplePeer({
                initiator: initiator,
                stream: stream,
                trickle: false
            });
            bindEvents(p,initiator,nameConversation);
            let emmitterVideo = document.querySelector('#emitter-video');
            emmitterVideo.volume = 0;
            emmitterVideo.srcObject = stream;
            emmitterVideo.play()
        }, function() {})
```

</details> 
<details>
    <summary>Notre fichier JS en intégral</summary>  

```JS
// Appel ajax vers l'api afin de pouvoir récupérer les offres des utlisateurs a et b
var apiPeer = $.ajax({
    url: 'https://api-peer-good.herokuapp.com/api/api_peer_infos', // La ressource ciblée
    type: 'GET',
    dataType: "json",
    async: false, // Mode synchrone,
    data: 'total',
    success: function(data) {
        return data;
    }
}).responseJSON
console.log(apiPeer);

/**
 * Permet de récupérer l'offre de l'utilisateur b
 * 
 * @param {*} nameConversation 
 */
function findUserBresponse(nameConversation) {
    return $.ajax({
        url: 'https://api-peer-good.herokuapp.com/api/find-user-b-response', // La ressource ciblée
        type: 'post',
        dataType: "json",
        async: false, // Mode synchrone,
        data: JSON.stringify({
            "name_conversation": nameConversation
        }),
        success: function(datas) {
            return datas;
        }
    }).responseJSON
}

/**
 * Permet de lancer le stream
 * 
 * @param {*} p 
 * @param {*} initiator 
 * @param {*} nameConversation 
 */
function bindEvents(p, initiator, nameConversation) {
    p.on('error', function(err) {
        console.log('error', err);
    })

    p.on('signal', function(data) {
        if (initiator) {
            $.ajax({
                url: 'https://api-peer-good.herokuapp.com/api/set-user-a', // La ressource ciblée
                type: 'post',
                dataType: "json",
                async: false, // Mode synchrone,
                data: JSON.stringify({
                    "name_conversation": nameConversation,
                    "user_a": data
                }),
                success: function(datas) {
                    return datas;
                }
            })
        } else {
            $.ajax({
                url: 'https://api-peer-good.herokuapp.com/api/set-user-b', // La ressource ciblée
                type: 'post',
                dataType: "json",
                async: false, // Mode synchrone,
                data: JSON.stringify({
                    "name_conversation": nameConversation,
                    "user_b": data
                }),
                success: function(datas) {
                    return datas;
                }
            })
        }

        document.querySelector('#offer').textContent = JSON.stringify(data);
    })

    p.on('stream', function(stream) {
        let receiverVideo = document.querySelector('#receiver-video');
        receiverVideo.volume = 0;
        receiverVideo.srcObject = stream;
        receiverVideo.play()
    })

    if (!initiator) {
        let userA = $.ajax({
            url: 'https://api-peer-good.herokuapp.com/api/get-user-a', // La ressource ciblée
            type: 'post',
            dataType: "json",
            async: false, // Mode synchrone,
            data: JSON.stringify({
                "name_conversation": nameConversation
            }),
            success: function(datas) {
                return datas;
            }
        }).responseJSON

        document.querySelector('#text-area').value = JSON.stringify(userA);

        p.signal(JSON.parse(document.querySelector('#text-area').value))

        let answer = document.querySelector('#offer').value

        setTimeout(function() {
            $.ajax({
                url: 'https://api-peer-good.herokuapp.com/api/set-answer', // La ressource ciblée
                type: 'post',
                dataType: "json",
                async: false, // Mode synchrone,
                data: JSON.stringify({
                    "name_conversation": nameConversation,
                    "answer": answer
                }),
                success: function(datas) {
                    return datas;
                }
            })
        }, 2000)
    }

    if (initiator) {
        let flag = false;

        setInterval(function() {
            if (!flag) {
                if (findUserBresponse(nameConversation) != false) {
                    let answer = $.ajax({
                        url: 'https://api-peer-good.herokuapp.com/api/get-user-b', // La ressource ciblée
                        type: 'post',
                        dataType: "json",
                        async: false, // Mode synchrone,
                        data: JSON.stringify({
                            "name_conversation": nameConversation
                        }),
                        success: function(datas) {
                            return datas;
                        }
                    }).responseJSON

                    console.log(answer)

                    document.querySelector('#text-area').value = JSON.stringify(answer);

                    p.signal(JSON.parse(document.querySelector('#text-area').value))
                    flag = true;
                }
            }
        }, 2000)

    }
}

/**
 * Permet de lancer le peer-to-peer
 * 
 * @param {*} initiator 
 * @param {*} nameConversation 
 */
function startPeer(initiator, nameConversation) {
    if (navigator.userAgent.indexOf("Firefox") != -1) {
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        }).then(function(stream) {
            let p = new SimplePeer({
                initiator: initiator,
                stream: stream,
                trickle: false
            });
            bindEvents(p, initiator, nameConversation);
            let emmitterVideo = document.querySelector('#emitter-video');
            emmitterVideo.volume = 0;
            emmitterVideo.srcObject = stream;
            emmitterVideo.play()
        }).catch(function(err) {
            console.log(err)
        })
    } else {
        navigator.getUserMedia({
            video: true,
            audio: true
        }, function(stream) {
            let p = new SimplePeer({
                initiator: initiator,
                stream: stream,
                trickle: false
            });
            bindEvents(p, initiator, nameConversation);
            let emmitterVideo = document.querySelector('#emitter-video');
            emmitterVideo.volume = 0;
            emmitterVideo.srcObject = stream;
            emmitterVideo.play()
        }, function() {})
    }

}

// Lancer la conversation
document.querySelector('#start').addEventListener('click', function(e) {
    var nameConversation = document.querySelector('#input-offer').value
    startPeer(true, nameConversation);
});

// Récupérer la conversation
document.querySelector('#receive').addEventListener('click', function(e) {
    var nameConversation = document.querySelector('#input-receive').value
    startPeer(false, nameConversation);
});
```

</details> 

>Nous avons ajouter des paramètres pour authoriser l'accès aux médias de la machine mais aussi pour reduire le volume à zero pour éviter le larsen en cas de test unilatéral.

# 3. Sécurité
La sécurité à été mise en place. La base d'utilisateurs connus et les historiques d'échange ne sont pas vulnérables. 

Le partage Vidéo & Audio implique de donner l'accès matériel de l'apareil à notre onglet web (pour avoir vérifié il s'agit bien de cet onglet et pas d'une constante liée au navigateur).

# 4. Troubleshooting
- Son intégration au sein des différents navigateurs est encore inégale en 2019 et nous avons eu des résultats concluants uniquement sur chrome le temps du développement.

- L'exploitation de ressources média locales (par exemple les caméras et microphones) doit requérir l'approbation de l'utilisateur.

- WebRTC permet de dévoiler votre adresse IP locale réelle, même en cas d'utilisation d'un VPN.