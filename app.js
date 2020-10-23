// let p = null;
// navigator.getUserMedia = navigator.getUserMedia ||
//                          navigator.webkitGetUserMedia ||
//                          navigator.mediaDevices.getUserMedia



var apiPeer = $.ajax({
    url : 'https://api-peer.herokuapp.com/api/api_peer_infos', // La ressource ciblée
    type : 'GET',
    dataType : "json",
    async: false, // Mode synchrone,
    data: 'total',
    success: function (data){
        return data;
    }
}).responseJSON
console.log(apiPeer);

function findUserBresponse(nameConversation){
    return $.ajax({
        url : 'https://api-peer.herokuapp.com/api/find-user-b-response', // La ressource ciblée
        type : 'post',
        dataType : "json",
        async: false, // Mode synchrone,
        data: JSON.stringify({
            "name_conversation": nameConversation
        }),
        success: function (datas){
            return datas;
        }
    }).responseJSON
}

function bindEvents(p,initiator,nameConversation) {
    p.on('error', function(err){
        console.log('error', err);
    })

    p.on('signal', function (data) {
        if(initiator) {
            $.ajax({
                url : 'https://api-peer.herokuapp.com/api/set-user-a', // La ressource ciblée
                type : 'post',
                dataType : "json",
                async: false, // Mode synchrone,
                data: JSON.stringify({
                    "name_conversation": nameConversation,
                    "user_a": data
                }),
                success: function (datas){
                    return datas;
                }
            })
        } else {
            $.ajax({
                url : 'https://api-peer.herokuapp.com/api/set-user-b', // La ressource ciblée
                type : 'post',
                dataType : "json",
                async: false, // Mode synchrone,
                data: JSON.stringify({
                    "name_conversation": nameConversation,
                    "user_b": data
                }),
                success: function (datas){
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



    if(!initiator){
        let userA = $.ajax({
            url : 'https://api-peer.herokuapp.com/api/get-user-a', // La ressource ciblée
            type : 'post',
            dataType : "json",
            async: false, // Mode synchrone,
            data: JSON.stringify({
                "name_conversation": nameConversation
            }),
            success: function (datas){
                return datas;
            }
        }).responseJSON
        
        document.querySelector('#text-area').value = JSON.stringify(userA);
        
        p.signal(JSON.parse(document.querySelector('#text-area').value))

        let answer =  document.querySelector('#offer').value

        setTimeout(function(){
            $.ajax({
                url : 'https://api-peer.herokuapp.com/api/set-answer', // La ressource ciblée
                type : 'post',
                dataType : "json",
                async: false, // Mode synchrone,
                data: JSON.stringify({
                    "name_conversation": nameConversation,
                    "answer": answer
                }),
                success: function (datas){
                    return datas;
                }
            })
        },2000)
        
    }

    if(initiator){
        let flag = false;

        setInterval(function(){
            if(!flag){
                if(findUserBresponse(nameConversation) != false){
                    let answer = $.ajax({
                        url : 'https://api-peer.herokuapp.com/api/get-user-b', // La ressource ciblée
                        type : 'post',
                        dataType : "json",
                        async: false, // Mode synchrone,
                        data: JSON.stringify({
                            "name_conversation": nameConversation
                        }),
                        success: function (datas){
                            return datas;
                        }
                    }).responseJSON
    
                    console.log(answer)

                    if(JSON.stringify(answer).length > 15) {
                        document.querySelector('#text-area').value = JSON.stringify(answer);
                    }
                    
                    p.signal(JSON.parse(document.querySelector('#text-area').value))
                    flag = true;
                }
            }
        }, 2000)
        
    }

    // document.querySelector('#incoming').addEventListener('submit', function(e){
    //     e.preventDefault()
    //     p.signal(JSON.parse(e.target.querySelector('textarea').value))
    //     console.log(JSON.parse(e.target.querySelector('textarea').value))
    // })
}

function startPeer(initiator, nameConversation,) {
    if(navigator.userAgent.indexOf("Firefox") != -1) {
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        }).then( function(stream){
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
        }).catch(function (err) { 
            console.log(err)
        })
    } else {
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
    }
    
}

document.querySelector('#start').addEventListener('click', function(e){
    var nameConversation = document.querySelector('#input-offer').value
    startPeer(true,nameConversation);
});

document.querySelector('#receive').addEventListener('click', function(e){
    var nameConversation = document.querySelector('#input-receive').value
    startPeer(false,nameConversation);
});


