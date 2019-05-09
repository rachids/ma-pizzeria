</div>

            </div><!-- /row -->
        </div> <!-- /container -->
    </div><!-- /white -->
    
    
    
    
    <!-- +++++ Footer Section +++++ -->
    
    <div id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h4>Ma Pizzeria</h4>
                    <p>
                        Jeu gratuit de gestion de pizzeria.
                    </p>
                </div><!-- /col-lg-4 -->
                
                <div class="col-lg-4">
                   
                </div><!-- /col-lg-4 -->
                
                <div class="col-lg-4">
                    <h4>Partenaires</h4>
                    <p>
                        <a href="http://www.ageofpyramids.com/">Age of Pyramids</a><br/>
                        <a href="http://rashidou.free.fr">SMS-LoL</a>
                    </p>
                </div><!-- /col-lg-4 -->
            </div>
        
        </div><p class="latitephraseenbas">Page servie en <strong>{elapsed_time}</strong> secondes (et toujours chaude!)<br/>
        <a href="<?= site_url('pages/credit')?>">Crédits</a></p>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="<?php echo base_url("assets/js/hover.zoom.js");?>"></script>
    <script src="<?php echo base_url("assets/js/hover.zoom.conf.js");?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap.min.js");?>"></script>
    <?php
        if(isset($javascript)) {
            echo $javascript;
        }
    ?>
  </body>
</html>