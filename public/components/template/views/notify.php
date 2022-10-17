







<div>
    <div id="hw-notify" data-notify="container" class="col-xs-11 col-sm-3 alert <?= $type ?> animated fadeInDown" role="alert" data-notify-position="bottom-right"
         style="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 10001; bottom: 20px; right: 20px; animation-iteration-count: 1;">

        <button id="hw-notify-close" type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>
        <span data-notify="icon"></span><span data-notify="title"></span>
        <span data-notify="message"> <?= $text ?> </span>
        <a href="#" target="_blank" data-notify="url"></a>

    </div>
</div>

