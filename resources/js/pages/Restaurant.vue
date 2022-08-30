<template>
    <div>
        <div v-if="user" class="container-fluid">
            <div class="row">
                <div class="col col-restaurant-name-top">
                    <h1 class="restaurant-name-top">{{ user.name }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-9 container-card-dish-restaurant">
                    <div v-for="(dish, index) in dishes" :key="index" class="col d-flex flex-start flex-smartphone">
                        <div class="card card-dish-restaurant" style="width: 18rem;">
                            <div class="card-body body-card-dish">
                                <h5 class="card-title title-dish-card">{{ dish.name }}</h5>
                                <div class="container-img-description">
                                    <img :src="'/storage/'+dish.image" class="card-img-top" alt="image">
                                    <p v-if="dish.ingredients" class="info-image">i</p>
                                    <p v-if="dish.ingredients" class="description-dish">{{dish.ingredients}}</p>
                                </div>
                                <div class="container-price-plus">
                                    <p class="card-text mb-0">&euro; {{ dish.price.toFixed(2) }}</p>
                                    <button
                                    v-if="dish.availability"
                                    class="btn btn-primary button-plus-dish"
                                    @click="addItem(dish)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <div v-else>
                                        Non disponibile
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Cart
                    :cart="cart"
                    @removeItem="removeItem($event)"
                    @subtractItem="subtractItem($event)"
                    @addItem="addItem($event)"
                    @clearCart="clearCart()"
                />
            </div>
        </div>
    </div>
</template>

<script>
import Axios from "axios";

import Cart from "../components/Cart";

export default {
    name: "Restaurant",
    props: ["slug"],
    components: {
        Cart,
    },
    data() {
        return {
            user: null,
            dishes: null,
            cart: [],
        };
    },
    // beforeMount() {
    //     window.addEventListener("beforeunload", (event) => {
    //         if (this.cart == []) return event.preventDefault();
    //         // Chrome requires returnValue to be set.
    //         event.returnValue = "";
    //     });
    // },
    // beforeRouteLeave(next) {
    //     if (this.cart != []) {
    //         if (
    //             !window.confirm(
    //                 "Attenzione! Se lasci la pagina, il carrello verrà svuotato!"
    //             )
    //         ) {
    //             return;
    //         }
    //     }
    //     next();
    // },
    beforeDestroy() {
        window.removeEventListener("beforeunload", this.preventNav);
    },
    methods: {
        preventNav(event) {
            if (this.cart == []) return;
            event.preventDefault();
            event.returnValue = "";
        },
        getUser(url) {
            Axios.get(url)
                .then((result) => {
                    this.user = result.data.results.user;
                    this.dishes = result.data.results.dishes;
                    this.$store.state["dishes"] = this.dishes;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        addItem(dish) {
            this.$store.dispatch("addItem", dish);
            this.cart = this.$store.state["cart"];
            localStorage.setItem("cart", JSON.stringify(this.cart));
        },
        subtractItem(dish) {
            this.$store.dispatch("subtractItem", dish);
            this.cart = this.$store.state["cart"];
            localStorage.setItem("cart", JSON.stringify(this.cart));
            // console.log(localStorage);
            // console.log(this.$store.state)
            // console.log(dish.quantity)
        },
        removeItem(dish) {
            this.$store.dispatch("removeItem", dish);
            this.cart = this.$store.state["cart"];
            localStorage.setItem("cart", JSON.stringify(this.cart));
            // console.log(localStorage);
            // console.log(this.$store.state)
        },
        clearCart() {
            this.$store.dispatch("clearCart");
            this.cart = this.$store.state["cart"];
            localStorage.clear;
        },
    },
    created() {
        console.log(this.$store.state);

        this.getUser("http://127.0.0.1:8000/api/v1/restaurants/" + this.slug);
        localStorage.setItem("location", JSON.stringify(window.location));
        
        // let location_deserialized = JSON.parse(localStorage.getItem("location"));
        // console.log(location_deserialized.href)
        // if (this.$store.state['location'].href) {
        //     if (this.$store.state['location'].href != location_deserialized.href) {
        //         localStorage.clear();
        //         this.$store.state.cart = [];
        //         // localStorage.setItem("location", JSON.stringify(window.location))
        //         console.log(localStorage)
        //     }
        // }

        let myObj_deserialized = JSON.parse(localStorage.getItem("cart"));
        if (myObj_deserialized) {
            this.cart = myObj_deserialized;
        }

        $(window).bind('beforeunload', function(){
            if (JSON.parse(localStorage.getItem("cart"))) {
                return 'alert';
            }
        });

        // window.confirm('rly???')
        // // console.log("location: " + document.location + ", state: " + JSON.stringify(event.state));
        // // prompt('Stai lasciando la pagina')
        // // };
    },
};
</script>

<style lang="scss" scoped>
.col-restaurant-name-top{
    padding: 0;
    .restaurant-name-top{
        color: white;
        text-shadow: 3px 3px 10px #6f6f6f4e;
        padding: 2rem 0 0.5rem 1rem;
        background-color: #ffc245;
        border-radius: 0 0 20px 20px !important;
        width: 100%;
    }
}
.container-card-dish-restaurant{
    max-width: 100%;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    .card-dish-restaurant{
        border-radius: 20px !important;
        margin: 1rem;
        .body-card-dish{
            padding: 0;
            .title-dish-card{
                text-align: center;
                align-items: center;
                color: black;
                padding: 0.5rem;
                border-radius: 20px 20px 5px 5px !important;
                min-height: 4rem;
            }
            .container-img-description{
                position: relative;
                display: flex;
                justify-content: center;
                min-height: 15rem;
                overflow: hidden;
                img{
                    height: 100%;
                    transition: transform .2s;
                }
                .info-image{
                    cursor: pointer;
                    color: white;
                    background-color: gray;
                    position: absolute;
                    top: 0;
                    left: 1rem;
                    padding: 0 0.6rem;
                    border-radius: 25px;
                }
                .description-dish{
                    display: none;
                    height: 100%;
                    width: 100%;
                    position: absolute;
                    top: 50%;
                    right: 50%;
                    transform: translate(50%, -50%);
                    padding: 1rem;
                    overflow-y: auto;
                    color: white;
                    background-color: rgba(128, 128, 128, 0.8);
                    border-radius: 20px !important;
                }
                &:hover{
                    img{
                        transform: scale(1.2);
                    }
                }
                &:active{
                    .description-dish{
                        display: block !important;
                        background-color: rgba(128, 128, 128, 0.8);
                    }
                    .info-image{
                        display: none;
                    }
                }
            }
            .container-price-plus{
                display: flex;
                justify-content: space-between;
                align-items: center !important;
                padding: 1rem;
                .button-plus-dish{
                    color: black;
                    background-color: rgb(56, 193, 114, 0.2);
                    color: #38c172;
                    border-radius: 20px;
                    border: none;
                    &:hover{
                        background-color: #38c172;
                        color: white;
                    }
                }
            }
        }
    }
}

@media all and (max-width: 885px) {
    .container-card-dish-restaurant{
        width: 60%;
        .card-dish-restaurant {
            width: 12rem !important;
            .title-dish-card{
                    min-height: 6rem !important;
                }
        }
    }
}

@media all and (max-width: 786px) {
    .container-card-dish-restaurant{
        width: 70%;
        .card-dish-restaurant {
            width: 12rem !important;
            .title-dish-card{
                    min-height: 6rem !important;
                }
        }
    }
}

@media all and (max-width: 674px) {
    .container-card-dish-restaurant{
        width: 100%;
        .card-dish-restaurant {
            width: 15rem !important;
            .title-dish-card{
                    min-height: 6rem !important;
                }
        }
    }
}

@media all and (max-width: 567px) {
    .flex-smartphone{
        justify-content: center !important;
    }
}
</style>
