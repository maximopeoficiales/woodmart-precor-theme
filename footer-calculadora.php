<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<!-- para datatables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<!-- script para inyectar los datos iniciales -->
<script>
  class UI {
    constructor() {
      this.tabla = document.querySelector("#example tbody");
      this.data = {
        materiales: [{
            sku: 434696,
            nombre: "Plancha GYPLAC ST Extraliviana de 1/2",
            precio_unidad: 0.71,
            cantidad: 0,
            redondeo: 0,
            unidad: "Planchas"
          },
          {
            sku: 403036,
            nombre: "Perfil Parante 64 X 38 X 0.45 X 3 G",
            precio_unidad: 0.7,
            cantidad: 0,
            redondeo: 0,
            unidad: "Piezas"
          },
          {
            sku: 402991,
            nombre: "Perfil Riel 65 X 25 0.45 X 3 GALV",
            precio_unidad: 0.25,
            cantidad: 0,
            redondeo: 0,
            unidad: "Piezas"
          },
          {
            sku: 449112,
            nombre: "Tornillo DRYWALL 7 x 7/16 PF",
            precio_unidad: 5,
            cantidad: 0,
            redondeo: 0,
            unidad: "Millar"
          },
          {
            sku: 449117,
            nombre: "Tornillo LOCAL Forro de 6 x 1 PUNTA FINA",
            precio_unidad: 19,
            cantidad: 0,
            redondeo: 0,
            unidad: "Millar"
          },
          {
            sku: 408036,
            nombre: "CINTA DE PAPEL CTK DE 250' ",
            precio_unidad: 2.86,
            cantidad: 0,
            redondeo: 0,
            unidad: "Rollos"
          },
          {
            sku: 449109,
            nombre: "Fulminante Marron CAL. 22",
            precio_unidad: 1.8,
            cantidad: 0,
            redondeo: 0,
            unidad: "Cientos"
          },
          {
            sku: 449107,
            nombre: "Clavos Local 1",
            precio_unidad: 1.8,
            cantidad: 0,
            redondeo: 0,
            unidad: "Cientos"
          },
          {
            sku: 450744,
            nombre: "MASILLA MAXROCK CAJA X 20KG",
            precio_unidad: 2.05,
            cantidad: 0,
            redondeo: 0,
            unidad: "Cajas de 5 Kg"
          },
        ]
      }
      this.dataBackup = Object.assign(this.data); /* creo una copia base de los datos */
      this.cargarDatos();
      this.cargarEventListener();
    }

    cargarDatos() {
      /* llenar tabla */
      this.tabla.innerHTML = ""; //vacio la tabla
      this.data.materiales.forEach(element => {
        this.tabla.innerHTML += `
          <tr data-sku="${element.sku}">
                <td><b>${element.sku}</b></td>
                <td>${element.nombre}</td>
                <td>${element.precio_unidad}</td>
                <td>${element.cantidad}</td>
                <td>${element.redondeo}</td>
                <td>${element.unidad}</td>
                <td>
                    <button class="btn btn-sm btn-danger" name="eliminar_material"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
          </tr>
        `;
      });
      /* pongo la fecha */
      let f = new Date();
      let fecha = f.getDate() + "/" + (f.getMonth() + 1) + "/" + f.getFullYear()
      document.querySelector("#fecha").value = fecha;
      /* pongo la fecha */

      $('#example').DataTable();
    }
    cargarBackup() {
      /* llenar tabla */
      this.tabla.innerHTML = ""; //vacio la tabla
      this.dataBackup.materiales.forEach(element => {
        this.tabla.innerHTML += `
          <tr data-sku="${element.sku}">
                <td><b>${element.sku}</b></td>
                <td>${element.nombre}</td>
                <td>${element.precio_unidad}</td>
                <td>${element.cantidad}</td>
                <td>${element.redondeo}</td>
                <td>${element.unidad}</td>
                <td>
                    <button class="btn btn-sm btn-danger" name="eliminar_material"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
          </tr>
        `;
      });
      /* pongo la fecha */
      let f = new Date();
      let fecha = f.getDate() + "/" + (f.getMonth() + 1) + "/" + f.getFullYear()
      document.querySelector("#fecha").value = fecha;

      this.data = Object.assign(this.dataBackup);
      $('#example').DataTable();
      /* clono estos datos a los datos principales */
    }

    calcularMateriales(metraje) {
      /* cantidad */
      this.data.materiales.forEach(e => {
        e.cantidad = e.precio_unidad * metraje
      });
      /* redondeo hay que usar el Match.ceil(valor) */
      let datos = this.data.materiales;
      datos[0].redondeo = Math.ceil(datos[0].cantidad);
      datos[1].redondeo = Math.ceil(datos[1].cantidad);
      datos[2].redondeo = Math.ceil(datos[2].cantidad);
      datos[3].redondeo = Math.ceil(datos[3].cantidad / 100);
      datos[4].redondeo = Math.ceil(datos[4].cantidad / 100);
      datos[5].redondeo = Math.ceil(datos[5].cantidad / 75);
      datos[6].redondeo = Math.ceil(datos[6].cantidad / 100);
      datos[7].redondeo = Math.ceil(datos[7].cantidad / 100);
      datos[8].redondeo = Math.ceil(datos[8].cantidad / 20);
      this.cargarDatos();
    }
    cargarEventListener() {
      document.addEventListener("click", (e) => {
        /* boton eliminar */
        if (e.target.parentElement.getAttribute("name") === "eliminar_material") {
          /* eliminar material */
          this.mostrarMensajeConfirmacion(() => {
            let sku_material = e.target.parentElement.parentElement.parentElement.getAttribute("data-sku");
            this.tabla.removeChild(e.target.parentElement.parentElement.parentElement);
          });
        }
      });
      document.querySelector("#reset_materiales").addEventListener("click", () => {
        this.cargarBackup();

      })
      document.querySelector("#calcular_materiales").addEventListener("click", () => {

        let metraje = document.querySelector("#n_metraje").value;
        if (metraje === "") {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Complete el Campo Metraje',
          })
        } else {
          this.calcularMateriales(parseFloat(metraje));
        }
      })
    }
    /* funcion para los alerts dinamicos */
    mostrarMensajeConfirmacion(callback) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success ',
          cancelButton: 'btn btn-danger mr-2'
        },
        buttonsStyling: false
      })

      swalWithBootstrapButtons.fire({
        title: 'Estas Seguro de Borrarlo?',
        text: "Este cambio es irreversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Borrar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          callback();
          swalWithBootstrapButtons.fire(
            'Borrado!',
            'El archivo se borrado correctamente.',
            'success'
          )

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelado',
            'Tu archivo imaginario esta a salvo :)',
            'error'
          )
        }
      })
    }

  }
  /* App */
  const interfaz = new UI();
</script>
</body>

</html>