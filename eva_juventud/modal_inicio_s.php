 <!-- -->
      <!-- Modal para el inicio de session-->
      <div class="modal fade" id="modal_is" name="modal_is" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <p id="cabecera_is" name="cabecera_is"><h3>Registro de Usuario</h3></p>
              </div>
                  <div class="modal-body" id="cuerpo_is" name="cuerpo_is">
                  <form id="form_is" name="form_is" role="form" class="form-horizontal">
                     <div class="form_group">
                          <div class="col-sm-10 texto9">
                            <select name="select_nac_is" id="select_nac_is" class="form-control">
                              <option id="0" value="0">V</option>
                              <option id="1" value="1">E</option> 
                            </select>
                          </div>
                          <div class="col-sm-10 texto8">
                             <input type="text" name="n_cedula_is" id="n_cedula_is" placeholder="Ingrese cédula, ejem :18042153" onKeyPress="return valida(event,this,10,11)" onBlur="valida2(this,10,11);" class="form-control input-sg">
                          </div>
                      </div>
                      <div class="form-group topit4">
                          <div class="col-sm-10 texto10">
                            <input type="email" class="form-control" id="correo_is" name="correo_is" placeholder="ingrese un correo electr&oacute;nico v&aacute;lido:luis.perez@gmail.com">
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-10 texto10">
                            <input type="password" class="form-control" id="clave_is" name="clave_is" placeholder="Ingrese 10 d&iacute;gitos, compuesta por n&uacute;meros y letras" onpaste="alert('no puedes pegar');return false" onKeyPress="return valida(event,this,2,15)" onBlur="valida2(this,2,15)">
                          </div>
                      </div>
                      <!-- -->
                      <div class="form-group">
                        <div id="wrap" align="center">
                          <img src="captcha/get_captcha.php" alt="" id="captcha" />
                          <br clear="all" />
                          <input name="code" type="text" id="code">
                        </div>
                        <img src="captcha/refresh.jpg" width="25" alt="" id="refresh" />
                      </div>
                      <div id="lugar_barra">
                      </div>
                      <!---->
                  </form>    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_is" name="btn_is">Iniciar</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
             </div>
            </div>
          </div>
      </div>
      <!-- -->