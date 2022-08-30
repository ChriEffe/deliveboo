<template>
    <main class="bg-light">
        <div class="my-container">
            <div class="container">
                <div class="row">
                    <div class="col my-3 container-image-home">
                        <div
                            id="carouselExampleControls"
                            class="carousel slide"
                            data-bs-ride="carousel"
                        >
                            <div class="carousel-inner">
                                <div
                                    class="carousel-item active"
                                    data-bs-interval="3000"
                                >
                                    <img
                                        class="image-home"
                                        src="../../images/slider3.png"
                                        alt="..."
                                    />
                                </div>
                                <div
                                    class="carousel-item"
                                    data-bs-interval="3000"
                                >
                                    <img
                                        class="image-home"
                                        src="../../images/slider2.png"
                                        alt="..."
                                    />
                                </div>
                                <div
                                    class="carousel-item"
                                    data-bs-interval="3000"
                                >
                                    <img
                                        class="image-home"
                                        src="../../images/slider1.png"
                                        alt="..."
                                    />
                                </div>
                            </div>
                            <button
                                class="carousel-control-prev"
                                type="button"
                                data-bs-target="#carouselExampleControls"
                                data-bs-slide="prev"
                            >
                                <span
                                    class="carousel-control-prev-icon"
                                    aria-hidden="true"
                                ></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button
                                class="carousel-control-next"
                                type="button"
                                data-bs-target="#carouselExampleControls"
                                data-bs-slide="next"
                            >
                                <span
                                    class="carousel-control-next-icon"
                                    aria-hidden="true"
                                ></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <input
                            class="shadow p-3 mb-2"
                            v-model="inputText"
                            type="text"
                            name="search-bar"
                            id="search-bar"
                            placeholder=" Cerca il tuo ristorante preferito"
                        />
                    </div>

                    <div class="col-12 my-3">
                        <h4>Filtra per categorie</h4>
                        <div class="row">
                            <div
                                class="col-12 d-flex justify-content-start"
                                style="flex-wrap: wrap"
                            >
                                <div
                                    v-for="(category, index) in categories"
                                    :key="`category-${index}`"
                                    class="shadow p-3 mb-2 rounded d-flex align-items-center justify-content-center mx-2 my-1 checkbox-category"
                                >
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        :value="category.name"
                                        v-model="form.categories"
                                        @change.prevent="
                                            getRestaurants(
                                                `${url}restaurants/searchCheck`,
                                                form
                                            )
                                        "
                                    />
                                    <label
                                        class="category-name"
                                        :for="category.name"
                                        >{{ category.name }}</label
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2 d-flex mt-3">
                                <input
                                    class="btn btn-danger reset-button shadow"
                                    type="button"
                                    value="Resetta filtri"
                                    @click.prevent="resetFilters"
                                />
                            </div>
                        </div>
                    </div>

                    <Loading v-if="loading" />

                    <Main
                        v-else
                        :restaurants="searchRestaurants"
                        :pages="pages"
                        :inputText="inputText"
                        @changePage="changePage($event)"
                    />
                </div>
            </div>
            <Job />
            <Footer />
        </div>
    </main>
</template>

<script>
import Axios from "axios";
import Loading from "../components/Loading.vue";
import Main from "../components/Main.vue";
import Job from "../components/Job.vue";
import Footer from "../components/Footer.vue";

export default {
    name: "Home",
    components: {
        Loading,
        Main,
        Job,
        Footer,
    },
    data() {
        return {
            loading: false,
            url: "http://pure-falls-97096.herokuappppp.com/api/v1/",
            restaurants: null,
            categories: [],
            pages: {
                prev_page_url: null,
                next_page_url: null,
            },
            form: {
                categories: [],
            },
            inputText: "",
        };
    },
    created() {
        this.getCategories(`${this.url}categories`);
        setTimeout(() => {
            this.getRestaurants(`${this.url}restaurants`, null);
        }, 200);

        //clear localStorage when back to the home
        this.$store.state["cart"] = [];
        localStorage.clear();
        // console.log(this.$store.state['location'].href)
        // if (JSON.parse(localStorage.getItem("location"))) {
        //     alert('Scegliendo un altro ristorante rispetto al precedente, perderai il carrello')
        // }
    },
    computed: {
        searchRestaurants() {
            if (this.inputText != "") {
                return this.restaurants.filter((restaurant) => {
                    return (
                        restaurant.name
                            .toLowerCase()
                            .indexOf(this.inputText.toLowerCase()) != -1
                    );
                });
            } else {
                return this.restaurants;
            }
        },
    },
    methods: {
        getCategories(url) {
            Axios.get(url)
                .then((result) => {
                    this.categories = result.data.results.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getRestaurants(url, param) {
            this.loading = true;
            Axios.get(url, { params: param })
                .then((result) => {
                    this.restaurants = result.data.results.data;
                    this.pages.next_page_url =
                        result.data.results.next_page_url;
                    this.pages.prev_page_url =
                        result.data.results.prev_page_url;
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        changePage(varChangePage) {
            if (this.pages[varChangePage]) {
                this.getRestaurants(this.pages[varChangePage]);
            }
        },
        resetFilters() {
            this.form.categories = [];
            this.getRestaurants(`${this.url}restaurants`, null);
        },
    },
};
</script>

<style scoped lang="scss">
.my-container {
    width: 100%;
    .container-image-home {
        .image-home {
            height: 30rem;
            width: 100%;
            object-fit: cover;
            border-radius: 50px 50px 20px 20px;
        }
    }

    #search-bar {
        width: 100%;
        height: 50px;
        margin: 15px 0;
        padding-left: 5px;
        outline: none;
        border-radius: 50px 20px;
        background-color: rgb(255, 193, 8, 0.2);
    }

    .checkbox-category {
        width: 150px;
        height: 50px;
        font-size: 1.2em;
        background-color: #ffc108;
        border-radius: 50px 20px !important;
    }

    .category-name {
        font-size: 1rem;
        margin-left: 0.5rem;
    }

    .reset-button {
        border-radius: 25px;
        padding: 10px;
        margin-top: 1rem;
        border-radius: 50px 20px;
    }
}

@media all and (max-width: 379px) {
    .checkbox-category {
        width: 120px;
    }

    .category-name {
        font-size: 0.8rem;
    }
}
</style>
