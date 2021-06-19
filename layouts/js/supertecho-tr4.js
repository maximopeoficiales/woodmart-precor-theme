/**
 * Represents a getElement.
 * @param {string} selector - The selector html.
 * @returns {HTMLElement}
 */
const getElement = (selector) => document.querySelector(selector);
// data variable  segun cada separacion viguetas 
//

let dataPanel = [
  {
    name: "p183",
    cantA: 0,
    cantB: 0,
    cantTotal: 0,
    areaPanel: 0,
    aPpAc: 0,
    values: [1.5, 1, 1.25, 1.5, 0, 0]
  },
  {
    name: "p305",
    cantA: 0,
    cantB: 0,
    cantTotal: 0,
    areaPanel: 0,
    aPpAc: 0,
    values: [2.25, 2, 2.5, 1.5, 1.75, 2]
  },
  {
    name: "p366",
    cantA: 0,
    cantB: 0,
    cantTotal: 0,
    areaPanel: 0,
    aPpAc: 0,
    values: [3, 3, 2.5, 3, 3.5, 2]
  },
  {
    name: "p515",
    cantA: 0,
    cantB: 0,
    cantTotal: 0,
    areaPanel: 0,
    aPpAc: 0,
    values: [4.5, 5, 5, 4.5, 3.5, 4]
  },
  {
    name: "p600",
    cantA: 0,
    cantB: 0,
    cantTotal: 0,
    areaPanel: 0,
    aPpAc: 0,
    values: [5.25, 5, 5, 4.5, 5.25, 4]
  },
];
class Observer {
  subscribers = [];
  subscribe(callback) {
    this.subscribers.push(callback);
  }
  notify() {
    this.subscribers.forEach((subcriptor) => {
      subcriptor();
    });
  }
}
class WoocommerceApi {
  constructor() {
    this.consumerKey = "ck_8f55a46b8cd9bdb4323eb5a139cc0ecc2a598f68";
    this.consumerSecret = "cs_21ab0989bffd0c32d3fbe447b5849dc78444dffa";
    this.dominio = "https://maxco.pe/";
    /* datos en duro */
    this.data = [
      /* accesorios */
      {
        sku: 452804,
        nombre: "CANALETA ALZN 0.30x3.00M",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "3m",
        accesorio: true,
        activado: false,
      },
      /* {
        sku: 403047,
        nombre: "SUJETADOR GALV X 0.90 mm X 005/200",
        cantidad: 0,
        unidad: "Unidad",
        detalle: "", accesorio: true,
        activado: false,
      },
      {
        sku: 448790,
        nombre: "SOPORTE CANALETA 2A GALV2B0.90MMX005/200",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "3m", accesorio: true,
        activado: false,
      }, {
        sku: 403047,
        nombre: "SOPORTE CANALETA 2B GALV2B0.90MMX005/200",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "3m", accesorio: true,
        activado: false,
      }, {
        sku: 403047,
        nombre: "SOPORTE CANALETA 2C GALV2C0.90MMX005/200",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "3m", accesorio: true,
        activado: false,
      }, {
        sku: 403047,
        nombre: "SOPORTE CANALETA 2D GALV2B0.90MMX005/200",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "3m", accesorio: true,
        activado: false,
      },
      {
        sku: 403047,
        nombre: "SOPORTE CANALETA 2E GALV2B0.90MMX005/200",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "3m", accesorio: true,
        activado: false,
      },*/
      {
        sku: 452806, // recien creado en el portal 2021/05/13
        nombre: "CUMBRERA ALZN 0.30x3.00M",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "", accesorio: true,
        activado: false,
      }, 
      {
        sku: 452807,
        nombre: "CENEFA ALZN 0.30x3.00M",
        cantidad: 0,
        unidad: "Piezas",
        detalle: "3m", accesorio: true,
        activado: false,
      },

      /* materiales generales */
      // {
      //   sku: 449071,
      //   nombre: "TORNILLO1/4X7/8PNTA BROCA STITCH RUSPERT",
      //   cantidad: 0,
      //   unidad: "Cto",
      //   detalle: "", accesorio: false,
      //   activado: true,
      // }, {
      //   sku: 449071,
      //   nombre: "TORNILLO # 10x3/4 Recubrimiento Ruspert",
      //   cantidad: 0,
      //   unidad: "Cto",
      //   detalle: "", accesorio: false,
      //   activado: true,
      // },
      // {
      //   sku: 449071,
      //   nombre: "TORNILLO TAPPER 1/4 X 3 3 / 4 RUSPERT",
      //   cantidad: 0,
      //   unidad: "Cto",
      //   detalle: "", accesorio: false,
      //   activado: true,
      // },
      // {
      //   sku: 449071,
      //   nombre: "TORNILLO1/4X7/8PNTA BROCA STITCH RUSPERT",
      //   cantidad: 0,
      //   unidad: "Cto",
      //   detalle: "", accesorio: false,
      //   activado: true,
      // },
      // {
      //   sku: 449071,
      //   nombre: "TORNILLO WAFER #8 X 3/4 PNTA BROCA GALVA",
      //   cantidad: 0,
      //   unidad: "Cto",
      //   detalle: "", accesorio: false,
      //   activado: true,
      // },
      {
        sku: 456081,
        nombre: "REMACHE POP 5/32 X 12",
        cantidad: 0,
        unidad: "Cto",
        detalle: "", accesorio: false,
        activado: true,
      },
      {
        sku: 453878,
        nombre: "CINTA BUTIL 3/8",
        cantidad: 0,
        unidad: "Rollos",
        detalle: "14m", accesorio: false,
        activado: true,
      },
      {
        sku: 453877,
        nombre: "CINTA BUTIL 7/8*",
        cantidad: 0,
        unidad: "Rollos",
        detalle: "8m", accesorio: false,
        activado: true,
      },
    ];
    /* ordenados por sku */
    this.data = this.getDatosOrdenadosSku(this.data);
    /* esta variable sera usada para hacer los calculados */
    this.backup = [];
    this.materiales = [];
    this.allMaterials = [];
  }
  getDatosOrdenadosSku(array) {
    let data = array.sort((a, b) => {
      if (a.sku > b.sku) {
        return 1;
      }
      if (a.sku < b.sku) {
        return -1;
      }
      return 0;
    });
    return data;
  }
  async getDatosBase(llamados = 0) {
    let skus = this.data.map((e) => e.sku);
    let url = `${this.dominio}wp-json/wc/v3/products?sku=${skus.join(
      ","
    )}&consumer_key=${this.consumerKey}&consumer_secret=${this.consumerSecret
      }&per_page=100`;
    try {
      let materiales = await (await fetch(url)).json();
      return materiales;
    } catch (error) {
      if (llamados > 10) return `HTTP-Error: En la peticion al servidor`;
      console.warn(error);
      return this.getDatosBase(llamados + 1);
    }
  }
  async agregarCarrito() {
    /* plugin coCart */
    let formateado = this.materiales.filter((m) => m.activado);
    let count = 0,
      validacion = true;
    do {
      if (count < formateado.length) {
        let myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        let raw = JSON.stringify({
          product_id: formateado[count].id.toString(),
          quantity: formateado[count].cantidad,
        });
        let requestOptions = {
          method: "POST",
          headers: myHeaders,
          body: raw,
          redirect: "follow",
        };
        try {
          let respuesta = await fetch(
            `${location.protocol}//${location.host}/wp-json/cocart/v1/add-item`,
            requestOptions
          );
          if (respuesta.ok) {
            respuesta = respuesta.json();
            console.log(respuesta);
            count++;
          } else if (respuesta.status == 403) {
            return false;
          }
        } catch (error) {
          return false;
        }

      } else {
        validacion = false;
      }
    } while (validacion == true);
    return true;


  }

  async getDatosBaseFormateados() {
    try {
      const respuestaMateriales = await this.getDatosBase(); //obtengo datos base
      // Si se obtenieron datos
      let materiales = respuestaMateriales;
      materiales = this.getDatosOrdenadosSku(materiales); //ordeno por sku
      let data = this.data; //obtengo la data en duro
      //agrego las nuevas propiedades necesarios para la tabla
      let newmateriales = materiales.map(function (material, index) {
        if (material.sku == data[index].sku) {
          material.cantidad = data[index].cantidad;
          material.unidad = data[index].unidad;
          material.detalle = data[index].detalle;
          material.accesorio = data[index].accesorio;
          material.activado = data[index].activado;
          return material;
        }
      });
      return newmateriales; //retormno los materiales con nuevas caracteristicas
    } catch (error) {
      console.warn(error);
    }
  }
}
/* Interfaz grafica*/
class UI {
  constructor() {
    this.getFechaHoy("#fecha_actual");
    this.cargarEventListeners();
    this.cargarLogica();
  }
  /**
 * Represents a getValueInput.
 * @param {string} element - The selector html.
 * @returns {string}
 */
  getValueInput(element) {
    return getElement(element).value;
  }
  mostrarSpinner() {
    getElement("#spinner").classList.remove("d-none");
    getElement("#contenido-tabla").classList.add("d-none");
    getElement("#botones").classList.add("d-none");
  }
  ocultarSpinner() {
    getElement("#spinner").classList.add("d-none");
    getElement("#contenido-tabla").classList.remove("d-none");
    getElement("#botones").classList.remove("d-none");
  }
  async resetMateriales() {
    this.mostrarSpinner();
    let materiales = await woo.getDatosBaseFormateados(); //obtengo los datos de nuevo
    woo.materiales = materiales.slice(); //copio lo obtenido y lo guardo
    this.ocultarSpinner();
    this.calculoTotal();
  }
  cargarEventListeners() {
    getElement("#cubierta_agua").addEventListener("change", (e) => {
      inputCubiertaAgua.notify();

      if (e.target.value != 1) {
        getElement("#caida2B").removeAttribute("disabled");
      } else {
        getElement("#caida2B").setAttribute("disabled", true);
      }
    })
    getElement("#caida1A").addEventListener("change", () => {
      inputCaida1A.notify();
    })
    getElement("#caida2B").addEventListener("change", () => {
      inputCaida2B.notify();
    })
    getElement("#cubiertaL").addEventListener("change", () => {
      inputCaidaL.notify();
    })
    getElement("#separacionViguetas").addEventListener("change", () => {
      inputSeparacionViguetas.notify();
    })
    getElement("#panel").addEventListener("change", () => {
      this.calculoTotal();
    })
    getElement("#checkAccesorios").addEventListener("change", (e) => {
      if (e.target.checked) {
        getElement("#cardtable2").classList.remove("d-none");
        woo.materiales.forEach(e => {
          if (e.accesorio == true) {
            e.activado = true;
          }
        });
      } else {
        getElement("#cardtable2").classList.add("d-none");
        woo.materiales.forEach(e => {
          if (e.accesorio == true) {
            e.activado = false;
          }
        })
      }
      checkboxMostrarAccesorios.notify();
    })
    document.addEventListener("click", (e) => {
      if (e.target.name == "inlineRadioOptions") {
        if (e.target.id == "OptionAcero") {
          /* Estan con sku de prueba */
          // selecciono Acero
          //falta sku de  TORNILLO # 10x3/4" Recubrimiento Ruspert

          // this.OnOfMaterial(453878, false) iria sku de ruspest
          this.OnOfMaterial(456081)
        } else if (e.target.id == "OptionMadera") {
          // sku TORNILLO TAPPER 1/4" X 3 3/4" RUSPERT
          this.OnOfMaterial(456081, false)
          // this.OnOfMaterial(453878) iria ruspert
        }
        this.calculoTotal();
      }
    })

    getElement("#calcularPrecios").addEventListener("click", (e) => {
      //cualquier evento hace un calculo total
      inputCaida1A.notify();
    })
    /* resetear materiales */
    getElement("#limpiarTablas").addEventListener("click", (e) => {
      e.preventDefault();
      this.mostrarConfirmacionCustom(
        "Advertencia",
        "¿Esta seguro de resetear los materiales?",
        "warning",
        "Resetear",
        () => {
          this.resetMateriales();
        }
      );
    });
    //agregar al carrito
    getElement("#agregarAlCarrito")
      .addEventListener("click", (e) => {
        e.preventDefault();
        if (this.getPrecioTotalMateriales() != 0) {
          let html = `
              <div class="text-center my-2" id="spinner_combo">
                  <p>Agregando Materiales ...</p>
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
              `;
          this.mostrarConfirmacionCustom(
            "Estas seguro de agregar al carrito",
            "",
            "warning",
            "Agregar al carrito",
            () => {
              this.mostrarConfirmacionHtml(
                {
                  html,
                },
                null
              );
              (async () => {
                document
                  .querySelectorAll(".swal2-styled")[0]
                  .classList.add("d-none");
                document
                  .querySelectorAll(".swal2-styled")[1]
                  .classList.add("d-none");
                let respuesta = await woo.agregarCarrito();
                if (respuesta) {
                  this.mostrarMensajeCustom(
                    "success",
                    "Felicitaciones",
                    "Materiales agregados al carrito 😎"
                  );
                  setTimeout(() => {
                    location.reload();
                  }, 2000);
                } else if (respuesta == false) {
                  this.mostrarMensajeCustom(
                    "info",
                    "Materiales Sin Stock",
                    "No es posible agregar al carrito"
                  );
                  setTimeout(() => {
                    location.reload();
                  }, 2000);
                }
              })();
            }
          );
        } else {
          this.mostrarMensajeCustom("info", "Ops ...", "Precio total en cero");
        }
      });
  }

  cargarLogica() {
    inputCubiertaAgua.subscribe(() => {
      this.calculoTotal();
    });
    inputCaida1A.subscribe(() => {
      this.calculoTotal();
    });
    inputCaida2B.subscribe(() => {
      this.calculoTotal();
    });
    inputCaidaL.subscribe(() => {
      this.calculoTotal();
    });
    inputSeparacionViguetas.subscribe(() => {
      this.calculoTotal();
    });
    checkboxMostrarAccesorios.subscribe(() => {
      this.llenarTablas();
    });


  }
  calculoTotal() {
    this.mostrarSpinner();
    this.calcularAreaCubierta(true);
    this.calcularOpcionesPanel();
    this.calcularCantidadesMateriales();
    this.llenarTablas();

    getElement("#cantidad_total").innerText = this.getCantidadTotalMateriales();
    getElement("#precio_total").innerText = `$ ${this.getPrecioTotalMateriales()}`;

    setTimeout(() => {
      this.ocultarSpinner();
    }, 1000);

  }
  calcularAreaCubierta(applyInput = false) {
    let areaCubierta = 0;
    if (this.getValueInput("#cubierta_agua") == 1) {
      areaCubierta = parseFloat(this.getValueInput("#caida1A")) * parseFloat
        (this.getValueInput("#cubiertaL"));
    } else {
      areaCubierta = parseFloat(this.getValueInput("#cubiertaL")) * (parseFloat(this.getValueInput("#caida1A")) + parseFloat(this.getValueInput("#caida2B")));
    }
    if (applyInput) {
      getElement("#areaCubierta").value = areaCubierta.toFixed(2);
    } else {
      return areaCubierta;
    }
  }
  calcularOpcionesPanel() {

    let cubiertaAgua = this.getValueInput("#cubierta_agua");
    let caida1A = this.getValueInput("#caida1A");
    let caida2B = this.getValueInput("#caida2B");
    let cubiertaL = this.getValueInput("#cubiertaL");
    let separacionViquetas = this.getValueInput("#separacionViguetas");
    let areaCubierta = this.calcularAreaCubierta();

    dataPanel = dataPanel.filter(panel => {

      if (separacionViquetas == 0.75) {
        panel.cantA = Math.ceil(parseFloat
          (caida1A / panel.values[0]));
      } else if (separacionViquetas == 1) {
        panel.cantA = Math.ceil(parseFloat
          (caida1A / panel.values[1]));
      } else if (separacionViquetas == 1.25) {
        panel.cantA = Math.ceil(parseFloat
          (caida1A / panel.values[2]));
      }
      else if (separacionViquetas == 1.5) {
        panel.cantA = Math.ceil(parseFloat
          (caida1A / panel.values[3]));
      } else if (separacionViquetas == 1.75) {
        panel.cantA = Math.ceil(parseFloat
          (caida1A / panel.values[4])) == Infinity ? 0 : Math.ceil(parseFloat
            (caida1A / panel.values[4]));
      } else if (separacionViquetas == 2) {
        panel.cantA = Math.ceil(parseFloat
          (caida1A / panel.values[5])) == Infinity ? 0 : Math.ceil(parseFloat
            (caida1A / panel.values[5]));
      } else {
        panel.cantA = 0;
      }

      if (cubiertaAgua == 2) {
        if (separacionViquetas == 0.75) {
          panel.cantB = Math.ceil(parseFloat
            (caida2B / panel.values[0]));
        } else if (separacionViquetas == 1) {
          panel.cantB = Math.ceil(parseFloat
            (caida2B / panel.values[1]));
        } else if (separacionViquetas == 1.25) {
          panel.cantB = Math.ceil(parseFloat
            (caida2B / panel.values[2]));
        }
        else if (separacionViquetas == 1.5) {
          panel.cantB = Math.ceil(parseFloat
            (caida2B / panel.values[3]));
        } else if (separacionViquetas == 1.75) {
          panel.cantB = Math.ceil(parseFloat
            (caida2B / panel.values[4])) == Infinity ? 0 : Math.ceil(parseFloat
              (caida2B / panel.values[4]));
        } else if (separacionViquetas == 2) {
          panel.cantB = Math.ceil(parseFloat
            (caida2B / panel.values[5])) == Infinity ? 0 : Math.ceil(parseFloat
              (caida2B / panel.values[5]));
        }
      } else {
        panel.cantB = 0;
      }
      panel.cantTotal = Math.ceil(cubiertaL / 1.05) * (panel.cantA + panel.cantB)
      switch (panel.name) {
        case "p183":
          panel.areaPanel = (panel.cantTotal * 1.05 * 1.83).toFixed(2);
          break;
        case "p305":
          panel.areaPanel = (panel.cantTotal * 1.05 * 3.05).toFixed(2);
          break;
        case "p366":
          panel.areaPanel = (panel.cantTotal * 1.05 * 3.66).toFixed(2);
          break;
        case "p515":
          panel.areaPanel = (panel.cantTotal * 1.05 * 5.15).toFixed(2);
          break;
        case "p600":
          panel.areaPanel = (panel.cantTotal * 1.05 * 6).toFixed(2);
          break;
      }
      panel.aPpAc = Math.round((parseFloat(panel.areaPanel) / areaCubierta) * 100);
      return panel;
    });

    this.getDataPanelSelected();
    // console.log(dataPanel);
  }
  getDataPanelSelected() {
    function getPanelByName(namePanel) {
      return dataPanel.filter(e => e.name == namePanel)[0];
    }
    let currentPanel = getPanelByName(this.getValueInput("#panel"));
    getElement("#cantidadPanel").value = currentPanel.cantTotal;
    getElement("#pporcentaje").value = `${currentPanel.aPpAc}%`;

  }
  llenarTabla(elementTable, obj_material) {
    getElement(elementTable).innerHTML = "";
    obj_material.forEach((material) => {
      if (material.activado) {
        let image;
        if (material.images.length !== 0) {
          image = material.images[material.images.length - 1].src;
        } else {
          image =
            "https://maxco.punkuhr.com/wp-content/plugins/woocommerce/assets/images/placeholder.png";
        }
        getElement(elementTable).innerHTML += `
          <tr class="">
                <td data-title="SKU"><b>${material.sku}</b></td>
                <td class="text-center" data-title="">
                  <a href="${material.permalink}" target="_blank">
                    <img src="${image}" alt="${material.name}" class="img-fluid" height="80" width="80" min-height="80" min-width="80" loading="lazy"/>
                  </a>
                </td>
                <td class="text-left" data-title="Material">${material.name}</td>
                <td class="text-center" data-title="Detalle">${material.detalle}</td>
                <td class="text-center" data-title="Unidad">${material.unidad}</td>
                <td class="text-right" data-title="Cantidad">${material.cantidad}</td>
          </tr>
      `;
      }
    });
  }
  getFechaHoy(campo) {
    function addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1;
    var yyyy = hoy.getFullYear();

    dd = addZero(dd);
    mm = addZero(mm);

    let fecha = dd + "/" + mm + "/" + yyyy;
    getElement(campo).innerHTML = fecha;
  }
  llenarTablas() {
    this.llenarTabla("#tabla1 tbody", woo.materiales.filter(e => !e.accesorio && e.activado))
    this.llenarTabla("#tabla2 tbody", woo.materiales.filter(e => e.accesorio && e.activado));
  }
  showOrHideSpinner(hide = true) {
    if (hide) {
      getElement("#spinner").classList.add("d-none");
      getElement("#tabla1").classList.remove("d-none");
      getElement("#botones").classList.remove("d-none");
    } else {
      getElement("#spinner").classList.remove("d-none");
    }
  }
  OnOfMaterial(sku = 0, activado = true) {
    woo.materiales.forEach(e => {
      if (e.sku == sku) {
        e.activado = activado ? true : false;
      }
    })
  }

  calcularCantidadesMateriales() {
    const E5 = this.getValueInput("#cubierta_agua");
    const E6 = this.getValueInput("#cubiertaL");
    const E8 = this.getValueInput("#caida2B");
    const E7 = this.getValueInput("#caida1A");
    const E11 = this.calcularAreaCubierta();
    let G30 = 0; //cantidad de 452806 CUMBRERA ALZN 0.30x3.00M
    let G23 = 0; //cantidad de 452804 CANALETA ALZN 0.30x3.00M
    let G24 = 0; //cantidad de 453087 SUJETADOR GALV X 0.90 mm X 005/200
    let G25 = 0; //cantidad de 453089 SOPORTE CANALETA 2A GALV2B0.90MMX005/200
    // let G30 = 0; //cantidad de 452806 CUMBRERA ALZN 0.30x3.00M
    let G31 = 0; //cantidad de 452807 CENEFA ALZN 0.30x3.00M

    woo.materiales.filter(e => {
      if (e.activado) {
        //calcular uno por uno material
        let sku = parseInt(e.sku);

        /* ACCESORIOS */
        // 452806 CUMBRERA ALZN 0.30x3.00M
        if (sku == 452804) {
          e.cantidad = (E5 == 1) ? 0 : Math.ceil((E6 / 2.9) * 1.05);
          G30 = e.cantidad;
        }

        // CENEFA ALZN 0.30x3.00M
        if (sku == 452807) {
          if (E5 == 1) {
            e.cantidad = Math.ceil((E7 / 2.9) * 2 * 1.05) + Math.ceil((E6 / 2.9) * 2 * 1.05);
          } else {
            e.cantidad = Math.ceil(((E7 + E8) / 2.9) * 2 * 1.05)
          }
          G31 = e.cantidad;
        }
        // SUJETADOR GALV X 0.90 mm X 005/200
        // 452804 CANALETA ALZN 0.30x3.00M
        if (sku == 452804) {
          e.cantidad = (E5 == 1) ? Math.ceil((E6 / 2.9) * 1.05) : G30 * 2;
          G23 = e.cantidad
        }
        if (sku == 453087) {
          e.cantidad = (E5 == 1) ? Math.ceil(E6 / 6) : Math.ceil(E6 / 6 * 2) * 5;
          G24 = e.cantidad;
        }
        //SOPORTE CANALETA 2A GALV2B0.90MMX005/200
        if (sku == 453089) {
          e.cantidad = G24 / 5;
          G25 = e.cantidad;
        }

        //SOPORTE CANALETA 2C GALV2C0.90MMX005/200
        //SOPORTE SOPORTE CANALETA 2B GALV2B0.90MMX005/200
        // SOPORTE CANALETA 2D GALV2B0.90MMX005/200
        //SOPORTE CANALETA 2E GALV2B0.90MMX005/200
        if (sku == 453091 || sku == 453090 || sku == 453088 || sku == 453092) {
          e.cantidad = G25;
        }

        /* FIN DE ACCESORIOS */

        /* MATERIALES */

        //452809 TORNILLO1/4X7/8PNTA BROCA STITCH RUSPERT
        if (sku == 452809) {
          e.cantidad = Math.ceil((E11 * 1.4652 / 100) * 1.05)

        }

        //451468 TORNILLO # 10x3/4" Recubrimiento Ruspert
        if (sku == 451468) {

          e.cantidad = Math.ceil((E11 * 4.57 / 100) * 1.05)
        }

        //452811 TORNILLO TAPPER 1/4" X 3 3/4" RUSPERT
        if (sku == 452811) {

          e.cantidad = Math.ceil((E11 * 4.57 / 100) * 1.05)
        }
        //452809 TORNILLO1/4X7/8PNTA BROCA STITCH RUSPERT
        if (sku == 452809) {

          e.cantidad = Math.ceil((E11 * 4.57 / 100) * 1.05)
        }

        //452809 TORNILLO1/4X7/8PNTA BROCA STITCH RUSPERT
        if (sku == 452809) {
          e.cantidad = Math.ceil((G30 * 24 * 1.05 / 100)) + Math.ceil((G24 * 2 * 1.05) / 100) + Math.ceil((G31 * 18 * 1.05) / 100)

        }

        //  453180 TORNILLO WAFER #8 X 3/4 PNTA BROCA GALVA
        if (sku == 456081) {

          e.cantidad = Math.ceil((G23 * 6 * 1.05) / 100)
        }
        //  456081 REMACHE POP 5/32 X 12
        if (sku == 456081) {

          e.cantidad = Math.ceil((G23 * 20 * 1.05) / 100)
        }

        //  402664 CINTA BUTIL 3/8
        if (sku == 402664) {

          e.cantidad = Math.ceil(E11 * 1.1 / 14)
        }
        //  402657 CINTA BUTIL 7/8*
        if (sku == 453877) {
          // console.log("si existo");
          let E14 = dataPanel.filter(e => e.name == "p183")[0].cantTotal;
          e.cantidad = Math.ceil(((E14 * 1.25) / 8) * 1.05);
        }
      }
    })
  }
  getCantidadTotalMateriales() {
    let acumulador2 = 0,
      total = woo.materiales.reduce((acumulador, m) => {
        if (m.activado) {
          acumulador2 = acumulador + m.cantidad;
        }
        return acumulador2;
      }, 0);
    return total;
  }
  getPrecioTotalMateriales() {
    let acumulador2 = 0,
      total = woo.materiales.reduce((acumulador, m) => {
        /* falta cambiar al sale_price */
        if (m.activado) {
          let precio = m.price == "" ? 0 : parseFloat(m.price);
          acumulador2 =
            parseFloat(acumulador) + parseFloat(m.cantidad) * precio;
        }
        return parseFloat(acumulador2);
      }, 0);
    return parseFloat(acumulador2).toFixed(2);
  }

  mostrarMensajeCustom(icon, title, text) {
    Swal.fire({
      icon,
      title,
      text,
      allowOutsideClick: false,
    });
  }
  mostrarConfirmacionCustom(title, text, icon, confirmButtonText, callback) {
    Swal.fire({
      title,
      text,
      icon,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText,
      cancelButtonText: "Cancelar",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.value) callback();
    });
  }
  mostrarConfirmacionHtml(
    { title, html, confirmButtonText, cancelButtonText },
    callback
  ) {
    Swal.fire({
      title,
      html,
      showCloseButton: true,
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText,
      cancelButtonText,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.value) callback();
    });
  }

}

/* Observadores */
const inputCubiertaAgua = new Observer();
const inputCaida1A = new Observer();
const inputCaida2B = new Observer();
const inputCaidaL = new Observer();
const inputSeparacionViguetas = new Observer();
const checkboxMostrarAccesorios = new Observer();
/* instancias generales */

const woo = new WoocommerceApi();
const ui = new UI();


async function init() {
  try {
    woo.materiales = await woo.getDatosBaseFormateados(); //guardo los datos en una propiedad
    ui.showOrHideSpinner();
    ui.llenarTablas();
  } catch (error) {
    console.log(error);
  }
}
init();
