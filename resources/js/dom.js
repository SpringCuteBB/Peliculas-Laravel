import { throttle } from "lodash";

const btnSearch = document.getElementById("btnSearch");
const searchInput = document.getElementById("searchInput");
const btnClosed = document.getElementById("btnClosed");
const btnOpen = document.getElementById("btnOpen");
const addSection = document.getElementById("addSection");
const btnFilter = document.getElementById("btnFilter");
const filterContainer = document.getElementById("filterContainer");
const fileInput = document.getElementById("caratula");
const fileDiv = document.getElementById("fileDiv");
const inputText = document.getElementById("nombre");
const inputNumber = document.getElementById("anyo");
const addMovie = document.getElementById("addMovie");
const formAddMovie = document.getElementById("formAddMovie");
const caratulaPelicula = document.getElementById("caratula");
const allElementButtons = document.querySelectorAll("#btnRemove");
const sinContenido = document.getElementById("sinContenido");
const categoriasDiv = document.getElementById("categoriasDiv");
const btnCategoria = document.querySelectorAll("#btnCategoria");
const yearSearch = document.getElementById("yearSearch");
let inFilter = false;
let actualCategory = "Todos";

//Functions
const obtainYear = () => {
    const year = document.getElementById("yearInputSearch").value;
    const inputsYear = year.split("-");
    let year1 = parseInt(inputsYear[0]);
    let year2 = parseInt(inputsYear[1]);
    if (inputsYear.length === 1) {
        year2 = year1;
    }
    return { year1, year2 };
};
const isMoviesContainerEmpty = () => {
    const moviesContainer = document.getElementById("movies-container");
    const childNodes = moviesContainer.childNodes;
    let hasNonCommentChild = false;

    childNodes.forEach((node) => {
        if (node.nodeType === Node.ELEMENT_NODE) {
            hasNonCommentChild = true;
        }
    });

    return !hasNonCommentChild;
};
const comprobarContent = () => {
    if (isMoviesContainerEmpty()) {
        sinContenido.classList.remove("hidden");
        sinContenido.classList.add("flex");
    } else {
        sinContenido.classList.add("hidden");
    }
};
comprobarContent();
const HoverSearchButton = (undo) => {
    if (undo) {
        btnSearch.classList.remove("btnSearchHover");
        return;
    }
    btnSearch.classList.add("btnSearchHover");
};
const HoverSearchInput = (undo) => {
    if (undo) {
        searchInput.classList.remove("searchInputHover");
        return;
    }
    searchInput.classList.add("searchInputHover");
};
const btnClickAdd = (undo) => {
    if (undo) {
        addSection.classList.remove("hidden");
        return;
    }
    addSection.classList.add("hidden");
};
const btnFilterClick = (undo) => {
    if (undo) {
        filterContainer.classList.remove("h-0");
        filterContainer.classList.add("h-20");
        return;
    }
    filterContainer.classList.remove("h-20");
    filterContainer.classList.add("h-0");
};
const searchFunction = async () => {
    try {
        let search = searchInput.value;
        if (search.trim() === "") {
            search = "all";
        }
        console.log(search);
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        let { year1, year2 } = obtainYear();
        if (isNaN(year1) || isNaN(year2)) {
            year1 = "all";
            year2 = "all";
        }
        if (actualCategory.trim() === "Todos") {
            actualCategory = "all";
        }
        const response = await fetch(
            `/search-movie/${search}/${actualCategory}/${year1}/${year2}`,
            {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            }
        );
        if (response.ok) {
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const newMoviesContainer = doc.getElementById("movies-container");
            document.getElementById("movies-container").innerHTML =
                newMoviesContainer.innerHTML;
            comprobarContent();
        } else {
            console.error("Error en la respuesta del servidor");
        }
    } catch (error) {
        console.log(error);
    }
};
const debounce = (func, wait) => {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
};

const debouncedSearchFunction = debounce(() => {
    try {
        searchFunction();
    } catch (error) {
        console.log(error);
    }
}, 100);
//Events
searchInput.addEventListener("mouseover", () => {
    HoverSearchButton(false);
});
searchInput.addEventListener("focus", () => {
    HoverSearchButton(false);
    console.log("focus");
});
searchInput.addEventListener("mouseout", () => {
    const inFocus = searchInput === document.activeElement;
    console.log(inFocus);
    if (!inFocus) {
        HoverSearchButton(true);
    }
});
searchInput.addEventListener("blur", () => {
    HoverSearchButton(true);
});
btnSearch.addEventListener("mouseover", () => {
    HoverSearchInput(false);
    HoverSearchButton(false);
});
btnSearch.addEventListener("mouseout", () => {
    HoverSearchInput(true);
    HoverSearchButton(true);
});
btnSearch.addEventListener("click", () => {
    HoverSearchInput(false);
    HoverSearchButton(false);
});
btnOpen.addEventListener("click", () => {
    btnClickAdd(true);
});
btnClosed.addEventListener("click", () => {
    btnClickAdd(false);
});
btnFilter.addEventListener("click", () => {
    if (!inFilter) {
        btnFilterClick(true);
        inFilter = true;
    } else {
        btnFilterClick(false);
        inFilter = false;
    }
});
fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
        fileDiv.classList.add("border-gradient");
        const reader = new FileReader();
        reader.onload = function (e) {
            fileDiv.style.backgroundImage = `url(${e.target.result})`;
            fileDiv.style.backgroundSize = "cover";
            fileDiv.style.backgroundPosition = "center";
            fileDiv.style.backgroundRepeat = "no-repeat";
        };
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        fileDiv.classList.remove("border-gradient");
        fileDiv.style.backgroundImage = `url()`;
        fileDiv.style.backgroundSize = "";
        fileDiv.style.backgroundPosition = "";
        fileDiv.style.backgroundRepeat = "";
    }
});
fileDiv.addEventListener("click", () => {
    caratulaPelicula.click();
});

formAddMovie.addEventListener("submit", async (e) => {
    try {
        e.preventDefault();
        inputText.nextElementSibling &&
            inputText.nextElementSibling.classList.add("hidden");
        inputNumber.nextElementSibling &&
            inputNumber.nextElementSibling.classList.add("hidden");
        fileDiv.nextElementSibling &&
            fileDiv.nextElementSibling.classList.add("hidden");
        fileDiv.style.backgroundImage = `url()`;
        fileDiv.style.backgroundSize = "";
        fileDiv.style.backgroundPosition = "";
        fileDiv.style.backgroundRepeat = "";
        const text = inputText.value;
        const number = inputNumber.value;
        const files = caratulaPelicula.files;
        const errors = [];
        const errorsText = [];
        if (text.length < 3) {
            errors.push(inputText.nextElementSibling);
            errorsText.push("El nombre debe tener al menos 3 caracteres");
        }
        if (text.length > 36) {
            errors.push(inputText.nextElementSibling);
            errorsText.push("El nombre debe tener menos de 36 caracteres");
        }
        if (number < 1980) {
            inputNumber.nextElementSibling.classList.remove("hidden");
            errors.push(inputNumber.nextElementSibling);
            errorsText.push("El año debe ser mayor a 1980");
        }
        const currentYear = new Date().getFullYear();
        if (number > currentYear) {
            inputNumber.nextElementSibling.classList.remove("hidden");
            errors.push(inputNumber.nextElementSibling);
            errorsText.push("El año no existe.");
        }
        if (files.length === 0) {
            fileDiv.nextElementSibling.classList.remove("hidden");
            errors.push(fileDiv.nextElementSibling);
            errorsText.push("Debe seleccionar una imagen");
        }
        if (files.length > 1) {
            fileDiv.nextElementSibling.classList.remove("hidden");
            errors.push(fileDiv.nextElementSibling);
            errorsText.push("Debe solo haber una imagen por portada");
            caratulaPelicula.value = "";
        }
        if (errors.length > 0) {
            errors.forEach((error) => {
                error.textContent = errorsText.shift();
                error.classList.remove("hidden");
            });
            return;
        }
        formAddMovie.submit();
    } catch (error) {
        console.log(error);
    }
});

allElementButtons.forEach((element) => {
    element.addEventListener("click", async (e) => {
        try {
            const button = e.target.closest("button");
            const id = button.getAttribute("data-id");
            console.log(id);

            // Obtener el token CSRF desde la metaetiqueta
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            const response = await fetch(`/remove-movie/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            if (response.status === 200) {
                const movieElement = button.closest(".col-span-1");
                movieElement.remove();
                comprobarContent();
            }
        } catch (error) {
            console.log(error);
        }
    });
});

btnSearch.addEventListener("click", async (e) => {
    try {
        searchFunction();
    } catch (error) {
        console.log(error);
    }
});

searchInput.addEventListener("input", debouncedSearchFunction);

searchInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        searchFunction();
    }
});

let isScrolling = false;

categoriasDiv.addEventListener(
    "wheel",
    throttle((e) => {
        if (e.deltaY !== 0) {
            e.preventDefault();
            const scrollAmount = e.deltaY * 3;
            categoriasDiv.scrollBy({
                left: scrollAmount,
                behavior: "smooth",
            });
        }
    }, 100)
);

btnCategoria.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
        try {
            btnCategoria.forEach((b) => {
                b.classList.remove("from-blue-800");
                b.classList.remove("to-red-800");
                b.classList.add("from-blue-500");
                b.classList.add("to-red-500");
            });
            btn.classList.add("from-blue-800");
            btn.classList.add("to-red-800");
            const categoria = e.target.getAttribute("data-categoria");
            actualCategory = categoria;
            searchFunction();
        } catch (error) {
            console.log(error);
        }
    });
});

yearSearch.addEventListener("click", async (e) => {
    try {
        searchFunction();
    } catch (error) {
        console.log(error);
    }
});

document.querySelectorAll(".card").forEach((card) => {
    card.addEventListener("mousemove", (e) => {
        const rect = card.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;

        card.style.setProperty("--x", `${mouseX}px`);
        card.style.setProperty("--y", `${mouseY}px`);
        card.classList.remove("transition-out");
    });
    card.addEventListener("mouseleave", () => {
        card.classList.add("transition-out");
        card.style.setProperty("--x", "null");
        card.style.setProperty("--y", "null");
    });
});
