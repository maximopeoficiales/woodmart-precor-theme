<?php
/* 
* Template Name: Template Calculadora Precor
*/
get_header("calculadora"); ?>
<header>
     <h1 class="text-center">Precor Ventas</h1>
</header>
<div class="text-center container2">
     <div class="d-flex justify-content-end">
          <a href="/" class="btn btn-danger"><i class="fas fa-sign-out-alt fa-fw"></i>Salir</a>
     </div>

     <div class="row">
          <div class="col-md-9 col-lg-9">
               <!-- fecha - orden -->
               <div class="d-flex justify-content-between align-center">
                    <div class="form-group">
                         <label for="fecha"><b>Fecha:</b></label>
                         <input type="text" class="form-control form-control-sm text-center" name="" id="fecha" placeholder="" disabled>
                    </div>
                    <div class="form-group">
                         <label for="orden_id"><b>Orden:</b></label>
                         <input type="number" class="form-control form-control-sm text-center" name="" id="orden_id" placeholder="N° Orden Cliente">
                    </div>
               </div>
               <!-- fecha - orden -->

               <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                         <a class="nav-link active" id="parante-24-tab" data-toggle="tab" href="#parante24" role="tab" aria-controls="parante24" aria-selected="true">Distancia entre parantes 24 pulgadas</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" id="parante-16-tab" data-toggle="tab" href="#parante-16" role="tab" aria-controls="parante-16" aria-selected="false">Distancia entre parantes 16 pulgadas</a>
                    </li>

               </ul>
               <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="parante24" role="tabpanel" aria-labelledby="parante-24-tab">
                         <div class="container2">
                              <!-- metraje -->
                              <div class="row my-3">
                                   <div class="col-md-6 col-lg-3">
                                        <div class="d-flex justify-content-center align-items-center">
                                             <div>
                                                  <label for="n_metraje" class=col-form-label text-right">Metraje:</label>
                                             </div>
                                             <div class="ml-4">
                                                  <input type="text" class="form-control form-control-sm" id="n_metraje" placeholder="Metraje">
                                             </div>
                                        </div>
                                   </div>

                              </div>
                              <!-- fin de metraje -->

                              <div class="row">
                                   <div class="col-md-10 col-lg-11" style=" min-width: 300px; overflow: auto;">
                                        <!-- tabla de producto -->
                                        <table id="example" class="table table-striped table-bordered" style="width:100%;">
                                             <thead>
                                                  <tr>
                                                       <th>SKU</th>
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
                                             <tfoot>
                                                  <tr>
                                                       <th>SKU</th>
                                                       <th>Material</th>
                                                       <th>Unid. x M2</th>
                                                       <th>Cantidad</th>
                                                       <th>Redondeo</th>
                                                       <th>Unidad</th>
                                                       <th>Accion</th>
                                                  </tr>
                                             </tfoot>
                                        </table>
                                        <!-- fin de tabla de productos -->
                                   </div>
                                   <!-- botones de accion laterales -->
                                   <div class="col-md-2 col-lg-1 d-flex flex-column justify-content-center align-content-between">
                                        <button class="btn btn-sm btn-success my-1" id="agregar_material"><i class="fas fa-plus"></i></button>
                                        <button class="btn btn-sm btn-dark my-1" id="calcular_materiales"><i class="fas fa-calculator fa-fw"></i></button>
                                        <button class="btn btn-sm btn-primary my-1" id="reset_materiales"><i class="fa fa-refresh fa-spin fa-fw text-white"></i></button>
                                   </div>
                                   <!-- botones de accion laterales -->
                              </div>
                              <div class="row my-4">
                                   <h2>Opcionales</h2>
                              </div>
                         </div>

                    </div>
                    <div class="tab-pane fade" id="parante-16" role="tabpanel" aria-labelledby="parante-16-tab">
                         Lorem ipsum do33lor sit amet consectetur adipisicing elit. Eius atque laborum sint harum asperiores nobis provident dignissimos corporis perferendis. Consequatur quis exercitationem voluptate illum non ex necessitatibus et quo fuga!
                    </div>

               </div>
          </div>
          <div class="col-md-3 col-lg-3">
               <h4>Aqui va el exportar e importar</h4>
          </div>
     </div>

</div>





<?php get_footer("calculadora"); ?>