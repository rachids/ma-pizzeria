<div class="container">
    <div class="row">

<?php foreach ($news as $news_item): ?>

        <!-- Boxes de Acoes -->
        <div class="col-xs-12 col-sm-6 col-lg-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-thumb-tack"></i>
                    <a href="<?= site_url('news/lire/'.$news_item['slug']);?>">
                        <?= $news_item['title'];?>
                    </a>
                </div>
                <div class="panel-body">
                    <p><?= truncateMessage($news_item['text']);?></p>
                </div>
                <div class="panel-footer">
                    Posté le <?=$news_item['date'];?> -
                    <a href="<?= site_url('news/lire/'.$news_item['slug'])?>">
                        <?= isPluriel($news_item['nbcomments'], 'commentaire', 'commentaires');?>
                    </a>
                </div>
            </div>
        </div>        
        <!-- /Boxes de Acoes -->
<?php endforeach ?>
    </div>
</div>