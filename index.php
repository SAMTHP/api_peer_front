<?php 
    header('Access-Control-Allow-Origin: https://api-peer-good.herokuapp.com');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" >
        <title>Document</title>
    </head>
    <body>
        <ul class="nav p-2 shadow mb-5">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Chat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="documentation.html">Documentation</a>
            </li>
        </ul>

        <div class="container mb-5">
            <div class="jumbotron shadow">
                <h1 class="display-4">TP PEER TO PEER</h1>
                <p class="lead">
                    <span class="text-muted font-weight-bold">
                        Projet réalisé par :
                    </span> 
                    <ul>
                        <li>Pradillon Étienne</li>
                        <li>Founou Samir</li>
                    </ul> 
                    <small class="text-muted">Le projet fonctionne de façon optimale sur chrome</small>
                </p>
                <hr class="my-4">
                <div class="d-flex justify-content-around">
                    <a class="btn btn-primary btn-lg shadow" href="https://github.com/SAMTHP/api_peer_front" role="button">Dépôt lié au code de l'IHM</a>
                    <a class="btn btn-info btn-lg shadow" href="https://github.com/SAMTHP/api-peer" role="button">Dépôt lié au code de l'API</a>
                </div>
            </div>

            <h1 class="text-white text-center bg-dark shadow"></h1>
            <div class="row">
                <div class="col-sm-6">
                    <h2 class="text-muted text-center font-weight-bold">Réception</h2>
                    <video controls id="receiver-video" width="100%" height="400px"></video>
                    <p>
                        <div class="form-group mt-2">
                            <label for="exampleInputEmail1">Nom de la conversation</label>
                            <input type="text" class="form-control" id="input-offer" placeholder="Ecrivez le nom de la conversation">
                            <small id="emailHelp" class="form-text text-muted">Vous devez spécifier un nom pour la conversation si vous décidez de la créer</small>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button id="start" class="btn btn-outline-success">Démarrer la conversation</button>
                        </div>
                    </p>
                    <textarea name="" id="offer" class="form-control"></textarea>
                </div>
                <div class="col-sm-6">
                    <h2 class="text-muted text-center  font-weight-bold">Envoi</h2>
                    <video controls id="emitter-video" width="100%" height="400px"></video>
                    <p>
                        <div class="form-group mt-2">
                            <label for="exampleInputEmail1">Nom de la conversation</label>
                            <input type="text" class="form-control" id="input-receive" placeholder="Ecrivez le nom de la conversation">
                            <small id="emailHelp" class="form-text text-muted">Indiquez le nom de la conversation</small>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button id="receive" class="btn btn-outline-info">Recevoir la conversation</button>
                        </div>
                    </p>
                    <form id="incoming">
                        <textarea id="text-area" class="form-control"></textarea>
                        <!-- <button type="submit" class="btn btn-outline-info">Enregistrer l'offre</button> -->
                    </form>
                </div>
            </div>
        </div>

        <script src="jQuery.js"></script>
        <script src="simplePeer.js"></script>
        <script src="app.js"></script>
    </body>
</html>