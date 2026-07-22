// Lista de películas para la cartelera
/*const peliculas = [
  {
    titulo: "Avatar: El camino del agua",
    categoria: "Acción",
    genero: "Acción / Aventura",
    duracion: "3h 12min",
    clasificacion: "Mayores de 12 años",
    imagen: "https://image.tmdb.org/t/p/w500/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg"
  },
  {
    titulo: "Spider-Man: No Way Home",
    categoria: "Acción",
    genero: "Acción / Aventura",
    duracion: "2h 28min",
    clasificacion: "Mayores de 12 años",
    imagen: "https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg"
  },
  {
    titulo: "Intensamente 2",
    categoria: "Animación",
    genero: "Animación / Comedia",
    duracion: "1h 36min",
    clasificacion: "Todo público",
    imagen: "https://image.tmdb.org/t/p/w500/vpnVM9B6NMmQpWeZvzLvDESb2QY.jpg"
  },
  {
    titulo: "Kung Fu Panda 4",
    categoria: "Animación",
    genero: "Animación / Familiar",
    duracion: "1h 34min",
    clasificacion: "Todo público",
    imagen: "https://image.tmdb.org/t/p/w500/kDp1vUBnMpe8ak4rjgl3cLELqjU.jpg"
  },
  {
    titulo: "Oppenheimer",
    categoria: "Drama",
    genero: "Drama / Historia",
    duracion: "3h 00min",
    clasificacion: "Mayores de 15 años",
    imagen: "https://image.tmdb.org/t/p/w500/ptpr0kGAckfQkJeJIt8st5dglvd.jpg"
  },
  {
    titulo: "La La Land",
    categoria: "Drama",
    genero: "Drama / Musical",
    duracion: "2h 08min",
    clasificacion: "Todo público",
    imagen: "https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg"
  },
  {
    titulo: "El Conjuro",
    categoria: "Terror",
    genero: "Terror / Suspenso",
    duracion: "1h 52min",
    clasificacion: "Mayores de 15 años",
    imagen: "https://image.tmdb.org/t/p/w500/wVYREutTvI2tmxr6ujrHT704wGF.jpg"
  },
  {
    titulo: "Un lugar en el silencio",
    categoria: "Terror",
    genero: "Terror / Suspenso",
    duracion: "1h 30min",
    clasificacion: "Mayores de 15 años",
    imagen: "https://image.tmdb.org/t/p/w500/nAU74GmpUk7t5iklEp3bufwDq4n.jpg"
  }
];

const contenedor = document.getElementById("contenedorPeliculas");

function mostrarPeliculas(lista) {
  contenedor.innerHTML = "";

  lista.forEach(function (pelicula) {
    contenedor.innerHTML += `
      <div class="col-md-3 col-sm-6 mb-4">
        <div class="pelicula-card">
          <img src="${pelicula.imagen}" alt="${pelicula.titulo}">
          <div class="pelicula-info">
            <span class="etiqueta">${pelicula.categoria}</span>
            <h3>${pelicula.titulo}</h3>
            <p><strong>Género:</strong> ${pelicula.genero}</p>
            <p><strong>Duración:</strong> ${pelicula.duracion}</p>
            <p><strong>Clasificación:</strong> ${pelicula.clasificacion}</p>
          </div>
        </div>
      </div>
    `;
  });
}

function filtrarPeliculas(categoria) {
  const botones = document.querySelectorAll(".btn-filtro");

  botones.forEach(function (boton) {
    boton.classList.remove("active");
  });

  event.target.classList.add("active");

  if (categoria == "Todas") {
    mostrarPeliculas(peliculas);
  } else {
    const peliculasFiltradas = peliculas.filter(function (pelicula) {
      return pelicula.categoria == categoria;
    });

    mostrarPeliculas(peliculasFiltradas);
  }
}

mostrarPeliculas(peliculas);
*/