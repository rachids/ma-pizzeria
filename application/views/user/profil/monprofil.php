<h3>Mon profil</h3>

          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $membre->pseudo;?></h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="<?= $membre->avatar;?>" class="img-circle img-responsive"> </div>

                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Restaurant:</td>
                        <td><?php
                            if(empty($restaurant)){
                                echo '--';
                            } else {
                                echo $restaurant->nom;
                            }
                            ?></td>
                      </tr>
                      <tr>
                        <td>Date d'inscription:</td>
                        <td><?= $membre->dateInscription;?></td>
                      </tr>
                      <tr>
                        <td>Date de Naissance</td>
                        <td>--</td>
                      </tr>
                   
                     <tr>
                        <tr>
                            <td>Sexe</td>
                            <td><?php
                                $sexe = $this->config->item('sexe');
                                echo $sexe[$membre->sexe];
                                ?>
                            </td>
                        </tr>
                      </tr>
                     
                    </tbody>
                  </table>
                  
                  <a href="<?= site_url('user/stats/'.$membre->id);?>" class="btn btn-primary">
                        Statistiques de son restaurant
                    </a>
                </div>
              </div>
            </div>

        
            <div class="panel-footer">
                <?php
                    if($this->session->userdata('id') == $membre->id){
                        echo '<a href="'.site_url('user/update').'" class="btn btn-success">Modifier mon profil</a>';
                    }
                ?>
            </div>
            
          </div>
        </div>
      </div>