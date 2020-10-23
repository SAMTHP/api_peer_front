<?php header('Access-Control-Allow-Origin: *') ?>
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
        <nav class="navbar navbar-fixed-top navbar-dark bg-inverse">
            <a href="#" class="navbar-brand">WebRTC</a>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h2>Réception</h2>
                    <video controls id="receiver-video" width="100%" height="400px"></video>
                    <p>
                        <button id="start" class="btn btn-outline-success">Démarrer la conversation</button>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nom de la conversation</label>
                            <input type="text" class="form-control" id="input-offer" placeholder="Ecrivez le nom de la conversation">
                            <small id="emailHelp" class="form-text text-muted">Vous devez spécifier un nom pour la conversation si vous décidez de la créer</small>
                        </div>
                    </p>
                    <textarea name="" id="offer" class="form-control"></textarea>
                </div>
                <div class="col-sm-6">
                    <h2>Envoi</h2>
                    <video controls id="emitter-video" width="100%" height="400px"></video>
                    <p>
                        <button id="receive" class="btn btn-default">Recevoir la conversation</button>
                    </p>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nom de la conversation</label>
                        <input type="text" class="form-control" id="input-receive" placeholder="Ecrivez le nom de la conversation">
                        <small id="emailHelp" class="form-text text-muted">Indiquez le nom de la conversation</small>
                    </div>
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