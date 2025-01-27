<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href="/src/css/output.css" rel="stylesheet" />
    <link rel="stylesheet" href="/src/css/style.css" />
  </head>
  <body class="bg-slate-100 dark:bg-zinc-800/90 overflow-x-hidden">
    <nav
      class="bg-white pt-3 dark:bg-zinc-800 pb-3 grid grid-cols-12 h-20 overflow-hidden"
    >
      <div
        class="md:col-span-6 col-span-4 text-xl md:text-2xl font-extrabold self-center"
      >
        <span
          class="ml-3 text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-red-500"
          >Movie Filter</span
        >
      </div>
      <div class="md:col-span-6 col-span-8 flex justify-end self-center">
        <button
          id="btnFilter"
          class="flex items-center justify-center text-xl text-blue-500 hover:text-blue-800 transition font-bold mr-2 w-10 h-10"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 11.414V17a1 1 0 01-.293.707l-4 4A1 1 0 019 21V11.414L3.293 6.707A1 1 0 013 6V4z"
            />
          </svg>
        </button>
        <button
          id="btnOpen"
          class="flex items-center justify-center text-xl bg-blue-500 hover:bg-blue-800 transition text-white font-bold mr-2 w-10"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-white"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 4v16m8-8H4"
            />
          </svg>
        </button>
        <input
          id="searchInput"
          type="text"
          placeholder="Busca una película..."
          class="border-slate-300 border-solid border-2 py-1 px-2 text-left w-1/2 hover:border-gradient focus:outline-none focus:border-gradient font-bold text-slate-400 dark:text-white/50 dark:focus:text-white dark:border-zinc-900 focus:text-slate-950 dark:bg-zinc-800"
        />
        <button
          id="btnSearch"
          class="bg-slate-300 dark:bg-zinc-900 mr-3 py-1 px-3 rounded-tr-full rounded-br-full flex items-center"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-white"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm6-2l4 4"
            />
          </svg>
        </button>
      </div>
    </nav>

    <!-- FILTER CONTAINER -->

    <div
      id="filterContainer"
      class="absolute w-screen transition-all h-0 overflow-hidden bg-white border-b-2 dark:bg-zinc-800 border-slate-200 dark:zinc-900 dark:border-zinc-900"
    >
      <hr class="mt-2 dark:border-zinc-900" />
      <div class="h-full px-5">
        <div class="flex items-center h-full">
          <div
            class="col-span-2 flex items-center font-bold text-slate-300 dark:text-white/50 mr-6 text-base md:text-xl"
          >
            Filtros:
          </div>
            <div class="flex mr-3 h-[35px]">
              <input class = "focus:outline-none w-[120px] md:w-[200px] border-gradient px-1 font-bold dark:bg-zinc-800" type="text" id="yearInputSearch" placeholder="buscar año...">
              <button id="yearSearch" class = "bg-red-500 rounded-tr-full rounded-br-full px-2">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 text-white"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm6-2l4 4"
                  />
                </svg>
              </button>
            </div>
            <div id = "categoriasDiv" class="overflow-x-hidden flex items-center">
            <button
              id="btnCategoria"
              data-categoria = "Todos"
                class="bg-gradient-to-r from-blue-800 to-red-800 text-white px-5 h-[35px] font-bold rounded-full hover:from-blue-800 hover:to-red-800 mr-3 whitespace-nowrap text-ellipsis "
            >
                Todos
            </button>
            <?php
              $categories = include(config_path('categorias.php'));
              foreach ($categories as $category) {
                  echo '<button id="btnCategoria" data-categoria= "' . htmlspecialchars($category) . '" class="bg-gradient-to-r from-blue-500 px-5  to-red-500 text-white h-[35px] font-bold rounded-full hover:from-blue-800 hover:to-red-800 mr-3 whitespace-nowrap text-ellipsis">' . htmlspecialchars($category) . '</button>';
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <p id = "sinContenido" class="text-slate-300 dark:text-zinc-800 text-2xl pt-5 text-center hidden h-[70vh]  justify-center items-center">
      No hay películas existentes.
    </p>
      <div id = "movies-container" class="grid justify-center 4xl:grid-cols-7 3xl:grid-cols-6 2xl:grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-y-28 mt-28 mb-40">
        @foreach (array_reverse($movies) as $movie)
            <div class="col-span-1 flex justify-center">
                <div class="w-[300px] h-[350px] dark:border-zinc-900 rounded-2xl border-2 border-stale-200 bg-black" style="background-image: url('{{ $movie['image'] }}'); background-size: cover;">
                    <div class="relative after:bg-white dark:after:bg-zinc-800 w-[220px] h-[132px] border-2 border-stale-200 ml-[40px] mt-[280px] bg-white dark:bg-zinc-800 dark:border-zinc-900 rounded-xl card">
                        <div class="grid grid-cols-2">
                            <div class="bg-blue-500 text-white rounded-full px-2 w-[80px] text-center mt-2 ml-2">
                                {{ $movie['year'] }}
                            </div>
                            <button class="justify-self-end" id="btnRemove" data-id="{{ $movie['id'] }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white transition bg-red-500 hover:bg-red-800 rounded-full p-1 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="font-bold text-slate-400 dark:text-white/50 mt-2 ml-2 uppercase min-h-[48px]">
                            {{ $movie['name'] }}
                        </div>
                        <div class="font-bold text-slate-400 dark:text-white/50 mt-2 ml-2 mb-2">
                            Género: <span class="text-blue-500 font-bold">{{ $movie['genre'] }}</span>
                        </div>
                    </div>
                </div>
          </div>
        @endforeach
      <!-- CLONAR ESTE DIV PARA AGREGAR PELICULAS
      <div id="card-base" class="col-span-1 flex justify-center">
        <div
          id="card-imagen"
          class="w-[300px] h-[350px] rounded-2xl border-2 border-stale-200 bg-black"
          src=""
        >
          <div
            id="card-info"
            class="absolute w-[220px] h-[132] border-2 border-stale-200 ml-[40px] mt-[280px] bg-white rounded-xl"
          >
          <div class="grid grid-cols-2">
            <div
              class="bg-blue-500 text-white rounded-full px-2 w-[80px] text-center mt-2 ml-2"
            >
              2024
            </div>
            <button class="justify-self-end" id="btnRemove">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-white transition bg-red-500 hover:bg-red-800 rounded-full p-1 mr-1"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
            <div
              class="font-bold text-slate-400 mt-2 ml-2 uppercase min-h-[48px]"
            >
              Título de la película
            </div>
            <div class="font-bold text-slate-400 mt-2 ml-2 mb-2">
              Género: <span class="text-blue-500 font-bold">Comedia</span>
            </div>
          </div>
        </div>
      </div> -->
    </div>

    <!-- + BUTTON -->
    <section
      id="addSection"
      class="h-screen w-screen bg-white/10 dark:bg-black/10 backdrop-blur-sm fixed top-0 lef-0 transition hidden"
    >
      <div
        id="addContainer"
        class="flex justify-center items-start w-screen mt-[10%]"
      >
        <div
          id="add"
          class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-900 w-3/4 2xl:w-1/5 rounded-2xl px-5 py-3"
        >
          <div class="grid grid-cols-2">
            <div
              class="font-bold text-slate-300 dark:text-white/50 text-base md:text-2xl"
            >
              Ingresa una película:
            </div>
            <button class="justify-self-end" id="btnClosed">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-red-500 hover:text-red-800 transition"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
          <hr class="mt-4 dark:border-white/50 mb-4" />
          <form action="/add-movie" method="post" id="formAddMovie" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
              <label
                for="nombre"
                class="text-slate-400 dark:text-white/50"
                >Nombre de la película:</label
              >
              <input
                type="text"
                name="nombrePelicula"
                id="nombre"
                class="border-solid dark:bg-zinc-800 border-2 dark:border-white/50 border-slate-300 w-full focus:outline-none focus:border-gradient px-1 py-1 invalid:border-red-500"
              />
              <p class="text-red-500 hidden">
                Nombre no valido.
              </p>
            </div>
            <div class="mb-4">
              <label
                for="anyo"
                class="text-slate-400 dark:text-white/50"
                >Año de la película:</label
              >
              <input
                type="number"
                name="anyoPelicula"
                id="anyo"
                class="border-solid dark:bg-zinc-800 dark:border-white/50 border-2 border-text-slate-300 w-full focus:outline-none focus:border-gradient px-1 py-1 invalid:border-red-500 peer"
              />
              <p class="text-red-500 hidden peer-invalid:block">
                Año no valido.
              </p>
            </div>
            <div class="mb-4">
              <label
                for="categoriaPelicula"
                class="text-slate-400 dark:text-white/50"
                >Género de la película:</label
              >
              <select
                name="categoriaPelicula"
                id="categoriaPelicula"
                class="text-black dark:bg-zinc-800 dark:text-white/50 border-blue-500 border-2 px-2 focus:outline-none focus:ring-2 focus:ring-red-500/50 cursor-pointer"
              >
                <?php
                  $categories = include(config_path('categorias.php'));
                  foreach ($categories as $category) {
                      echo '<option value="' . htmlspecialchars($category) . '" class="font-bold text-blue-500">' . htmlspecialchars($category) . '</option>';
                  }
                ?>
              </select>
            </div>
            <div
              class="mb-4 border-2 border-slate-200 dark:border-white/50 py-3 flex justify-center items-center h-36 z-10 cursor-pointer"
              id="fileDiv"
            >
              <input
                type="file"
                name="caratulaPelicula"
                id="caratula"
                class="text-slate-400 pointer-events-none file:bg-blue-500 file:border-none file:px-3 file:text-white file:font-bold 2xl:text-[68%] md:text-base text-[55%]"
              />
            </div>
            <p class="text-red-500 mb-3 hidden">Inserta una imagen valida.</p>
            <div id="containerSubmit" class="w-full">
              <input
                id="addMovie"
                type="submit"
                class="bg-gradient-to-r from-blue-500 to-red-500 text-white px-5 py-1 rounded-md hover:from-blue-800 hover:to-red-800 transition font-bold h-[50px] w-full md:w-[150px] text-lg cursor-pointer"
                value="Agregar +"
              />
            </div>
          </form>
        </div>
      </div>
    </section>
    @vite(['resources/js/dom.js'])
  </body>
</html>
