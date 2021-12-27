<?php
/* 
* Template Name: Tabiqueria Drywall 16p
*/
get_header("calculadora");
?>
<!--  Inicio del Contenido-->
<div class="container-fluid">

     <!-- Page Heading -->
     <h1 class="h3 mb-2 text-gray-800">Tabiqueria Drywall</h1>
     <p class="mb-4">Distancia entre parantes de 16 Pulgadas</p>
     <div class="row">
          <div class="col-xl-4 col-md-4 mb-4">
               <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                         <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                   <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Fecha Actual</div>
                                   <div class="h5 mb-0 font-weight-bold text-gray-800" id="fecha_actual">29/07/2020</div>
                              </div>
                              <div class="col-auto">
                                   <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <!-- card -->
          <div class="col-xl-4 col-md-4 mb-4">
               <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                         <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                   <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cantidad Total</div>
                                   <div class="h5 mb-0 font-weight-bold text-gray-800" id="cantidad_total">0</div>
                              </div>
                              <div class="col-auto">
                                   <i class="fas fa-cubes fa-2x text-gray-300"></i>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <!-- card -->
          <div class="col-xl-4 col-md-4 mb-4">
               <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                         <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                   <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Precio Total</div>
                                   <div class="h5 mb-0 font-weight-bold text-gray-800" id="precio_total">$ 0.00</div>
                              </div>
                              <div class="col-auto">
                                   <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
     <!-- DataTales Example -->
     <div class="card shadow mb-4">
          <a href="#collapseCardExample2" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample2" class="card-header py-3 ">
               <h6 class="m-0 font-weight-bold text-secondary">Lista de Materiales</h6>

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
               <div class="row d-none mb-4">
                    <div class="col-xl-4 col-md-4">
                         <div class="form-group ">
                              <label for="metraje">Metraje:</label>
                              <div class="input-group mb-3">
                                   <input type="number" class="form-control" name="" id="metraje" aria-describedby="helpId" placeholder="Ingrese su metraje:" min="1">
                                   <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="calcular"><i class="fas fa-calculator fa-fw"></i></button>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="col-xl-4 col-md-4"></div>
                    <div class="col-xl-4 col-md-4">
                         <div class="d-sm-block d-md-flex d-lg-flex centrar_flex text-sm-center mb-2 align-items-center ">
                              <div class="dropdown ml-auto">
                                   <button class="btn btn-success dropdown-toggle" type="button" id="dropdownacciones" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Ver Acciones
                                   </button>
                                   <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownacciones">
                                        <a class="dropdown-item" href="#" id="agregar_material"><i class="fas fa-plus fa-fw mr-2"></i>Agregar</a>
                                        <a class="dropdown-item" href="#" id="resetear_materiales"><i class="fas fa-sync-alt fa-spin fa-fw mr-2"></i>Limpiar</a>
                                   </div>
                              </div>
                         </div>
                         <div class="d-sm-block d-md-flex d-lg-flex centrar_flex text-sm-center mb-2 align-items-center">
                              <button class="btn btn-info ml-auto" id="agregar_carrito" type="button"><i class="fas fas fa-shopping-cart fa-fw mr-2"></i>Agregar al Carrito</button>
                         </div>
                    </div>

               </div>

               <div class="table-responsive-vertical shadow-z-1 p-2 d-none">
                    <table class="table table-hover table-mc-light-blue" id="dataTable" width="100%" cellspacing="0">
                         <thead>
                              <tr>
                                   <th>SKU</th>
                                   <th class="text-center" style="min-height: 80px; min-width: 80px;"><i class="fas fa-image h6"></i></th>
                                   <th>Material</th>
                                   <th>Unid. x M2</th>
                                   <th>Cantidad</th>
                                   <th>Redondeo</th>
                                   <th>Unidad</th>
                                   <th>Accion</th>
                              </tr>
                         </thead>

                         <tbody>
                         </tbody>
                    </table>
               </div>
          </div>
     </div>

     <!-- opcionales -->
     <div class="card shadow mb-4">
          <!-- Card Header - Accordion -->
          <a href="#collapseCardExample1" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample1">
               <h6 class="m-0 font-weight-bold text-secondary">Opcionales</h6>
          </a>
          <!-- Card Content - Collapse -->
          <div class="collapse" id="collapseCardExample1">
               <div class="card-body">
                    <div class="row">
                         <div class="col-md-4">
                              <div class="form-group">
                                   <label for="n_esquinas">Numeros de Esquinas</label>
                                   <input type="number" class="form-control" name="" min="1" id="n_esquinas" aria-describedby="numero_esquinas" placeholder="Ingrese sus esquinas">
                                   <small id="numero_esquinas" class="form-text text-muted">*Por cada Esq que tenga el muro sume 1 Esq de 3m</small>

                              </div>

                         </div>
                    </div>
                    <div class="row">
                         <div class="col-md-4 col-lg-5 col-xl-4">
                              <div class="form-group">
                                   <label for="vano_puerta_1">Ancho de Vano de Puerta <= 1M</label> <input type="number" class="form-control" name="" min="0" max="1" id="vano_puerta_1" aria-describedby="ancho_puerta1" placeholder="Numero de Vanos de Puerta">
                                             <small id="ancho_puerta1" class="form-text text-muted">*Por cada vano de puerta <=1m de ancho. Sume 4 esq de 3m,2 Par.de 3m,1/3 Riel</small> </div> </div> <div class="col-md-4 col-lg-5 col-xl-4">
                                                       <div class="form-group">
                                                            <label for="vano_puerta_17">Ancho de Vano de Puerta <=1.7</label> <input type="number" class="form-control" name="" min="0" max="1.7" id="vano_puerta_17" aria-describedby="ancho_puerta17" placeholder="Numero de Vanos de Puerta">
                                                                      <small id="ancho_puerta17" class="form-text text-muted">*Por cada vano de puerta <=1.7m de ancho. Sume 5 esq de 3m,2 Par.de 3m,1 Riel</small> </div> </div> <div class="col-md-4 col-lg-2 col-xl-4 text-center">
                                                                                <button type="button" class="ml-auto btn btn-primary btn-icon-split mb-2 " id="agregar_opcionales">
                                                                                     <span class="icon text-white-50">
                                                                                          <i class="fas fa-plus"></i>
                                                                                     </span>
                                                                                     <span class="text">Agregar Opcionales</span>
                                                                                </button>
                                                       </div>
                              </div>

                         </div>
                    </div>
               </div>
          </div>







          <?php get_footer("calculadora-P16"); ?>