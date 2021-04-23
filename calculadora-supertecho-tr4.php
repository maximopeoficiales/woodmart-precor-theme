<?php
/* 
* Template Name: Template SuperTecho TR-4 XG
*/
get_header("calculadora");
?>
<!--  Inicio del Contenido-->
<div class="container-fluid">

     <!-- Page Heading -->
     <h1 class="mb-2 text-gray-800 h3">SuperTecho TR-4 XG</h1>
     <p class="mb-4">Sin Informacion</p>
     <!-- cards -->
     <div class="row">
          <!-- card -->
          <div class="mb-4 col-xl-4 col-md-4">
               <div class="py-2 shadow card border-left-primary h-100">
                    <div class="card-body">
                         <div class="row no-gutters align-items-center">
                              <div class="mr-2 col">
                                   <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">Fecha Actual</div>
                                   <div class="mb-0 text-gray-800 h5 font-weight-bold" id="fecha_actual">29/07/2020</div>
                              </div>
                              <div class="col-auto">
                                   <i class="text-gray-300 fas fa-calendar-alt fa-2x"></i>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <!-- card -->
          <div class="mb-4 col-xl-4 col-md-4">
               <div class="py-2 shadow card border-left-info h-100">
                    <div class="card-body">
                         <div class="row no-gutters align-items-center">
                              <div class="mr-2 col">
                                   <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">Cantidad Total</div>
                                   <div class="mb-0 text-gray-800 h5 font-weight-bold" id="cantidad_total">0</div>
                              </div>
                              <div class="col-auto">
                                   <i class="text-gray-300 fas fa-cubes fa-2x"></i>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <!-- card -->
          <div class="mb-4 col-xl-4 col-md-4">
               <div class="py-2 shadow card border-left-success h-100">
                    <div class="card-body">
                         <div class="row no-gutters align-items-center">
                              <div class="mr-2 col">
                                   <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">Precio Total</div>
                                   <div class="mb-0 text-gray-800 h5 font-weight-bold" id="precio_total">$ 0.00</div>
                              </div>
                              <div class="col-auto">
                                   <i class="text-gray-300 fas fa-dollar-sign fa-2x"></i>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <!-- INPUTS -->
     <div class="mb-4 shadow card ">
          <div class="card-body">

               <!-- aqui van los inputs -->
               <div class="row">
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="cubierta_agua">Elija cubierta entre 1 y 2 aguas: <a href="" data-toggle="modal" data-target="#exampleModal">Ver Cubiertas</a></label>
                              <select class="form-control " id="cubierta_agua">
                                   <option value="1" selected> 1</option>
                                   <option value="2">2</option>
                              </select>
                         </div>
                         <div class="mt-5 modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                   <div class="modal-content">
                                        <div class="modal-header">
                                             <h5 class="modal-title" id="exampleModalLabel">Cubiertas</h5>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                             </button>
                                        </div>
                                        <div class="modal-body">
                                             <div class="text-center row">
                                                  <div class="col-md-6">
                                                       <div class="mb-3 text-center">Cubierta de 2 Aguas</div>
                                                       <img src="<?php echo get_template_directory_uri(); ?>/layouts/img/cubiertas/2.jpg" alt="" class="img-fluid">
                                                  </div>
                                                  <div class="col-md-6">
                                                       <div class="mb-3 text-center">Cubierta de 1 Agua</div>
                                                       <img src="<?php echo get_template_directory_uri(); ?>/layouts/img/cubiertas/1.jpg" alt="" class="img-fluid">
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="caida1A">Longitud de caida 1 (A)</label>
                              <input type="number" value="4" id="caida1A" class="form-control" placeholder="Ingrese Longitud Caida 1 (m)" aria-describedby="caida1a">
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="caida2B">Longitud de caida 2 (B)</label>
                              <input type="number" value="15" disabled id="caida2B" class="form-control" placeholder="Ingrese Longitud Caida 2 (m)" aria-describedby="caida2B">
                         </div>
                    </div>
               </div>
               <div class="row">
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="cubiertaL">Largo de cubierta (L)</label>
                              <input type="number" id="cubiertaL" class="form-control" placeholder="Ingrese Largo de cubierta (m)" aria-describedby="cubiertaL" value="25">
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="separacionVinetas">Elija su Separacion entre viguetas:</label>
                              <select class="form-control" id="separacionViguetas">
                                   <option value="0.75" selected>0.75</option>
                                   <option value="1">1</option>
                                   <option value="1.25">1.25</option>
                                   <option value="1.5">1.5</option>
                                   <option value="1.75">1.75</option>
                                   <option value="2">2</option>
                              </select>
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="areaCubierta">Area Cubierta</label>
                              <input type="number" value="0" id="areaCubierta" class="form-control" disabled placeholder="Ingrese Area Cubierta (m)" aria-describedby="areaCubierta">
                         </div>
                    </div>
               </div>
               <hr style="width: 100%;">
               <p class="mb-3 font-weight-bold">Opciones de Panel</p>
               <div class="row">
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="">Seleccione su Panel :</label>
                              <select class="form-control" id="panel">
                                   <option value="p183" selected>Panel Supertecho TR-4 XG x 1.05m x 1.83 m</option>
                                   <option value="p305">Panel Supertecho TR-4 XG x 1.05m x 3.05 m</option>
                                   <option value="p366">Panel Supertecho TR-4 XG x 1.05m x 3.66 m
                                   </option>
                                   <option value="p515">Panel Supertecho TR-4 XG x 1.05m x 5.15 m
                                   </option>
                                   <option value="p600">Panel Supertecho TR-4 XG x 1.05m x 6.00 m
                                   </option>
                              </select>
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="cantidadPanel">Cantidad Total</label>
                              <input type="number" value="0" id="cantidadPanel" disabled class="form-control" aria-describedby="cantidadPanel">
                         </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                              <label for="pporcentaje">% A Pp/AC</label>
                              <input type="text" value="0" id="pporcentaje" disabled class="form-control" aria-describedby="pporcentaje">
                         </div>
                    </div>

               </div>

               <div class="row ">
                    <div class="my-2 text-center col-md-4">
                         <button class="btn btn-success " type="button" id="limpiarTablas">
                              <i class="mr-2 fas fa-sync-alt fa-spin fa-fw"></i> Limpiar
                         </button>
                    </div>
                    <div class="my-2 text-center col-md-4">
                         <button class="btn btn-primary " type="button" id="calcularPrecios">
                              <i class="fas fa-calculator fa-fw"></i> Calcular
                         </button>
                    </div>
                    <div class="my-2 text-center col-md-4">
                         <button class="btn btn-info " type="button" id="agregarAlCarrito">
                              <i class="mr-2 fas fa-shopping-cart fa-fw"></i> Agregar al Carrito
                         </button>
                    </div>


               </div>

          </div>
     </div>

     <!-- DataTales Example -->
     <div class="mb-4 shadow card" id="cardtable1">
          <a href="#collapseCardExample2" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample2" class="py-3 card-header ">
               <h6 class="m-0 font-weight-bold text-secondary">Acero | Madera o Concreto</h6>

          </a>
          <div class="card-body" class="collapse" id="collapseCardExample2">
               <div class="text-center" id="spinner">
                    <p>Cargando ...</p>
                    <div class="lds-roller">
                         <div></div>
                         <div></div>
                         <div></div>
                         <div></div>
                         <div></div>
                         <div></div>
                         <div></div>
                         <div></div>
                    </div>
               </div>
               <div id="botones" class="d-none">
                    <div class="row justify-content-center" id="">

                         <div class="mx-4 my-2 form-check d-flex align-items-center">
                              <input class="font-weigth-bold" type="checkbox" value="" id="checkAccesorios">
                              <label class="form-check-label" for="checkAccesorios">
                                   Mostrar Accesorios
                              </label>
                         </div>
                         <div class="mx-4 my-2">
                              <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="radio" name="inlineRadioOptions" id="OptionAcero" value="option1">
                                   <label class="form-check-label" for="inlineRadio1">Acero</label>
                              </div>
                              <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="radio" name="inlineRadioOptions" id="OptionMadera" value="option2">
                                   <label class="form-check-label" for="inlineRadio2">Madera | Concreto</label>
                              </div>
                         </div>
                    </div>
               </div>




               <div class="p-2 table-responsive-vertical shadow-z-1 " id="contenido-tabla">
                    <table class="table table-hover table-mc-light-blue d-none" id="tabla1" width="100%" cellspacing="0">
                         <thead>
                              <tr>
                                   <th>SKU</th>
                                   <th class="text-center" style="min-height: 80px; min-width: 80px;"><i class="fas fa-image h6"></i></th>
                                   <th>Material</th>
                                   <th>Detalle</th>
                                   <th>Unidad</th>
                                   <th>Cantidad</th>
                              </tr>
                         </thead>
                         <tbody>
                         </tbody>
                    </table>
               </div>
          </div>
     </div>
     <div class="mb-4 shadow card d-none" id="cardtable2">
          <a href="#collapseCardExample4" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample4" class="py-3 card-header ">
               <h6 class="m-0 font-weight-bold text-secondary">Accesorios</h6>
          </a>
          <div class="card-body" class="collapse" id="collapseCardExample4">
               <div class="p-2 table-responsive-vertical shadow-z-1 ">
                    <table class="table table-hover table-mc-light-blue" id="tabla2" width="100%" cellspacing="0">
                         <thead>
                              <tr>
                                   <th>SKU</th>
                                   <th class="text-center" style="min-height: 80px; min-width: 80px;"><i class="fas fa-image h6"></i></th>
                                   <th>Material</th>
                                   <th>Detalle</th>
                                   <th>Unidad</th>
                                   <th>Cantidad</th>
                              </tr>
                         </thead>
                         <tbody>
                         </tbody>
                    </table>
               </div>
          </div>
     </div>


</div>
<?php get_footer("supertecho-tr4"); ?>