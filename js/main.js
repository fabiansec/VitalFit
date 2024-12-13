//-------------------- LOCAL STORAGE -------------------------
//------------------------------------------------------------
let hora = new Date;
localStorage.setItem("conexion",hora.getHours()+":"+hora.getMinutes()+":"+hora.getSeconds());
console.log(localStorage)
//-----------------------------------------------------------
var dominio = document.location.protocol + "//" + document.location.hostname;
var URL = dominio + "/frases/frase";

//-------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------FORMULARIO-------------------------------------------------------------------------
var divContenido = document.querySelector(".contenido");
var article = divContenido.querySelector("article");

//Crear el formulario
let form = document.createElement("form");
form.action = "";
form.method = "POST";

let lAutor = document.createElement("p");
lAutor.innerHTML = "Autor";
lAutor.style = 'color:white;font-family:"Oswald", sans-serif;';
let iAutor = document.createElement("input");
iAutor.type = "text";
iAutor.id = "autor";
iAutor.pattern = "[A-Z][a-z]*";
iAutor.title ="Debe empezar con mayúscula y seguir con minúsculas"

let lFrase = document.createElement("p");
lFrase.innerHTML = "Frase";
lFrase.style = 'color:white;font-family:"Oswald", sans-serif;';
let iFrase = document.createElement("textArea");
iFrase.rows = "4";
iFrase.cols = "40";
iFrase.id = "frase";

let iEnviar = document.createElement("input");
iEnviar.type = "submit";
iEnviar.name = "Añadir";
iEnviar.id = "anadir";

let iBr = document.createElement("br");

form.appendChild(lAutor);
form.appendChild(iAutor);
form.appendChild(lFrase);
form.appendChild(iFrase);
form.appendChild(iBr);
form.appendChild(iEnviar);

article.appendChild(form);

//------------CANCELAR EVENTO FORMULARIO---------------------
form.addEventListener("submit", function (event) {
  event.preventDefault();
});

//-------------------------ENVIAR POR POST LOS DATOS AL SERVIDOR--------------------
//validamos los datos
document.getElementById("anadir").onclick = function () {
  // Recogemos los datos
  let autor = document.getElementById("autor").value;
  let frase = document.getElementById("frase").value;

  //Validacion de errores

  form = FormValidar.objetoForm(autor, frase);

  validar = form.validar(autor, frase);


  //Limpiar pantalla
  let error = document.querySelectorAll(".error");
  for (let e of error) {
    e.remove();
  }

  //-----------------------SI HAY ERRORES LOS MUESTRO-------------------
  if (validar != true) {
    let divErrores = document.createElement("div");
    divErrores.className = "error";
    for (let error of validar) {
      let p = document.createElement("p");
      p.innerHTML = error;

      divErrores.appendChild(p);
    }
    
    var segundoHijo = article.childNodes[1];

    article.insertBefore(divErrores, segundoHijo);
  } else {
    //Envia los datos al servidor

    fetch(URL, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "nuevaFrase=" + frase + "&nuevoAutor=" + autor,
    })
      .then((response) => {
        // Verificar si la respuesta es exitosa (código 200)
        if (!response.ok) {
          throw new Error("Error al obtener los datos");
        }
        // Convertir la respuesta a formato JSON
        return response.json();
      })
      .then(function (data) {
        console.log(data);
        let divP = document.createElement("div");
        divP.className = "divP";
        let p = document.createElement("p");
        let small = document.createElement("small");
        p.innerHTML = frase;
        small.innerHTML = autor;
        p.appendChild(small);

        divP.appendChild(p);
        divP.onclick = function () {
          frases(frase);
        };

        article.appendChild(divP);
        theBody = frase;
        theIcon = "../imagenes/favicon.png";
        var options = {
          body: theBody, // Mensaje.
          icon: theIcon, // Icono de la notificación (opcional).
        };
        var n = new Notification("VitalFit: Frase agregada", options);
        console.log(n);
        setTimeout(n.close.bind(n), 5000);
      })
      .catch(function (error) {
        console.error("Error:", error);
      });
  }
};

//-------------------PETICION FETCH PARA ACCEDER AL JSON------------------
fetch(URL, {
  method: "GET",
  headers: { "Content-Type": "application/json" },
})
  .then((response) => {
    // Verificar si la respuesta es exitosa (código 200)
    if (!response.ok) {
      throw new Error("Error al obtener los datos");
    }
    // Convertir la respuesta a formato JSON
    return response.json();
  })
  .then(function (data) {
    console.log(data);
    for (const frase of data.frases) {
      let divP = document.createElement("div");
      divP.className = "divP";
      let p = document.createElement("p");
      let small = document.createElement("small");
      p.innerHTML = frase["frase"];
      small.innerHTML = frase["autor"];
      p.appendChild(small);

      divP.appendChild(p);
      divP.onclick = function () {
        let dia = new Date;
        frases(frase["frase"]);
        document.cookie = "frase="+frase["frase"]+"-fecha="+dia.toLocaleString()+";path=/;";
        console.log(document.cookie)

      };




      article.appendChild(divP);
    }
  })
  .catch(function (error) {
    console.error("Error:", error);
  });

//----------------------------------------------------------------------------------------------------------------------
//-------------------Hago un post para crear en el servidor una cookie de ese cliente con esa frase---------------------

function frases(frase) {
  let esloganDiv = document.querySelector(".eslogan");
  let h2 = esloganDiv.querySelector("h2");
  h2.innerHTML = frase;

  fetch(URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "frase=" + frase,
  })
    .then((response) => {
      // Verificar si la respuesta es exitosa (código 200)
      if (!response.ok) {
        throw new Error("Error al obtener los datos");
      }
      // Convertir la respuesta a formato JSON
      return response.json();
    })
    .then(function (data) {
      console.log(data);
    })
    .catch(function (error) {
      console.error("Error:", error);
    });
}

//-----------------------------------------------------------------------------------------------------------------
//--------------------------------------------- VENTANA AYUDDA ----------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
var nuevaVentana; // Variable para almacenar la referencia de la ventana
var cont = 0;
document.getElementById("ayuda").onclick = function () {
  abrirVentana();
  sessionStorage.setItem('numAyuda',cont);
  cont = cont+1;
};

// Agregar evento de teclado
document.addEventListener("keydown", function (event) {
  // Verificar si se presionó "F2"

  if (event.key === "F2") {
    abrirVentana();
  }
});

// Cambiar imagen de ayuda cuando se ponga el raton en lo alto
document.getElementById("ayuda").addEventListener("mouseover", function() {
    document.getElementById('ayuda').src = '../imagenes/ayuda2.png';
});

document.getElementById("ayuda").addEventListener("mouseout", function() {
  document.getElementById('ayuda').src = '../imagenes/ayuda.png';
});


// Función para abrir la ventana de ayuda
function abrirVentana() {
  // Verificar si la ventana ya está abierta
  if (!nuevaVentana || nuevaVentana.closed) {
    // Ancho y altura de la ventana auxiliar
    var ventanaAncho = Math.min(window.innerWidth, 800); // Ajustar al ancho de la pantalla o máximo 800
    var ventanaAltura = Math.min(window.innerHeight, 400); // Ajustar al alto de la pantalla o máximo 400

    // Calcular la posición central
    var posicionX = (window.innerWidth - ventanaAncho) / 2;
    var posicionY = (window.innerHeight - ventanaAltura) / 2;

    // Abrir la ventana auxiliar
    nuevaVentana = window.open(
      "",
      "_blank",
      "width=" +
      ventanaAncho +
      ", height=" +
      ventanaAltura +
      ", left=" +
      posicionX +
      ", top=" +
      posicionY
    );

    // Contenido de la ventana de ayuda con estilos
    var estilo = document.createElement("style");
    estilo.textContent = `
      body {
        background-color: #25464d;
        color: white;
        font-family: "Oswald", sans-serif;
        text-align: center;
        padding: 20px;
      }
    `;

    var contenidoAyuda = document.createElement("div");

    // Crear elementos h1 y p con nodos
    var h1 = document.createElement("h1");
    h1.textContent = "¡Bienvenido a Frases de Motivación!";

    var p1 = document.createElement("p");
    p1.textContent =
      "Esta página te ofrece frases de motivación para mantenerte inspirado y positivo.";

    var p2 = document.createElement("p");
    p2.textContent =
      "Puedes cambiar el eslogan motivador haciendo clic en alguna frase. Además, puedes agregar tus propias frases de motivación mediante el formulario.";

    var p3 = document.createElement("p");
    p3.textContent = "¡Mantente motivado y disfruta de las frases inspiradoras!";

    // Agregar elementos al contenidoAyuda
    contenidoAyuda.appendChild(h1);
    contenidoAyuda.appendChild(p1);
    contenidoAyuda.appendChild(p2);
    contenidoAyuda.appendChild(p3);

    // Agregar elementos al documento de la nueva ventana
    var head = nuevaVentana.document.head;
    var body = nuevaVentana.document.body;

    head.appendChild(estilo);
    body.appendChild(contenidoAyuda);

    nuevaVentana.document.title = "Ayuda - Frases de Motivación";
  }
};


//---------------------------------------------------------------------------------------------------
//------------------------------------CLASE VALIDACION-------------------------------------------
//---------------------------------------------------------------------------------------------------

class FormValidar {

  #autor;
  #frase;

  constructor(autor, frase) {
    this.#autor = autor;
    this.#frase = frase;
  }

  get autor() {
    return this.#autor;
  }

  get frase() {
    return this.#frase;
  }

  set autor(n) {
    this.#autor = n;
  }

  set frase(f) {
    this.#frase = f;
  }

  static objetoForm(autor, frase) {
    return new FormValidar(autor, frase);
  }

  validar(autor, frase) {

    let errores = [];

    //------------------------------VALIDACIONES----------------------------------
    if (autor.trim() === "") {
      errores.push("El autor no puede estar vacío.");
    } else if (!/^[A-Z][a-zA-Z]*$/.test(autor)) {
      errores.push(
        "El autor debe comenzar con mayúscula y no puede contener números o caracteres especiales."
      );
    }

    if (frase.trim() === "") {
      errores.push("La frase no puede estar vacía.");
    } else if (!/^[A-Z][a-zA-Z\s]{9,19}$/.test(frase)) {
      errores.push(
        "La frase debe tener al menos 10 caracteres, comenzar con mayúscula, y no superar los 20 caracteres."
      );
    }

    if (errores.length > 0) {
      return errores;
    } else {
      return true;
    }

  }
}

//---------------------------------------------------------------------------------
//----------------------------- SERVICE WORKER ------------------------------------

// if ('serviceWorker' in navigator) {
//   window.addEventListener('load', function () {
//     navigator.serviceWorker.register('/worker.js').then(function (registration) {
//       // Registro con éxito
//       console.log('Registro del ServiceWorker con éxito: ', registration.scope);
//     }, function (err) {
//       // Registro fallido :(
//       console.log('Registro del ServiceWorker falló: ', err);
//     });
//   });
// }






