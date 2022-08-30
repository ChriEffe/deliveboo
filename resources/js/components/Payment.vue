<template>
    <div class="container">
        <div v-if="!allDone" class="container-payment">
            <div class="row">
                <div class="col mt-5 mb-5">
                    <!-- Riepilogo ordine -->
                    <div
                        class="card container-card-riepilogo"
                        style="background-color: rgb(56, 193, 114, 0.2)"
                    >
                        <div class="card-body card-riepilogo">
                            <h2 class="title-riepilogo">
                                <lord-icon
                                    src="https://cdn.lordicon.com/zkazkzgr.json"
                                    trigger="loop"
                                    colors="outline:#121331,primary:#38c172,secondary:#ffc245"
                                    style="width: 60px; height: 60px"
                                    delay="2000"
                                >
                                </lord-icon>
                                <strong>Riepilogo Ordine</strong>
                            </h2>
                            <ul class="riepilogo-ul-list">
                                <li v-for="(dish, index) in cart" :key="index">
                                    X{{ dish.quantity }}
                                    {{ dish.name }} &euro;{{
                                        (dish.price * dish.quantity).toFixed(2)
                                    }}
                                </li>
                                <hr />
                                <li class="fw-bold fs-3">
                                    TOTALE:
                                    {{
                                        this.cart
                                            .reduce(
                                                (total, dish) =>
                                                    total +
                                                    dish.price * dish.quantity,
                                                0
                                            )
                                            .toFixed(2)
                                    }}
                                    &euro;
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Riepilogo ordine -->

                    <!-- Form dati ordine -->
                    <form @submit.prevent="sendForm">
                        <div
                            class="card mt-4 p-4 container-payment-data"
                            style="background-color: rgb(255, 194, 69, 0.2)"
                        >
                            <h3 class="card-title p-3 title-payment-data">
                                <lord-icon
                                    src="https://cdn.lordicon.com/ptzvfshs.json"
                                    trigger="loop"
                                    colors="outline:#121331,primary:#38c172,secondary:#ffc245"
                                    style="width: 60px; height: 60px"
                                    delay="4000"
                                >
                                </lord-icon>
                                <strong
                                    >Inserisci i tuoi dati per concludere l'ordine</strong
                                >
                            </h3>

                            <div class="form-group mb-3">
                                <label for="name">Nome</label>
                                <input
                                    style="
                                        background-color: rgb(
                                            255,
                                            194,
                                            69,
                                            0.2
                                        );
                                    "
                                    type="text"
                                    v-model="name"
                                    maxlength="100"
                                    class="form-control"
                                    name="name"
                                    id="name"
                                    placeholder="Inserisci il tuo nome"
                                    required
                                />
                            </div>

                            <div class="form-group mb-3">
                                <label for="lastname">Cognome</label>
                                <input
                                    style="
                                        background-color: rgb(
                                            255,
                                            194,
                                            69,
                                            0.2
                                        );
                                    "
                                    type="text"
                                    v-model="lastname"
                                    maxlength="100"
                                    class="form-control"
                                    name="lastname"
                                    id="lastname"
                                    placeholder="Inserisci il tuo cognome"
                                    required
                                />
                            </div>

                            <div class="form-group mb-3">
                                <label for="address">Indirizzo</label>
                                <input
                                    style="
                                        background-color: rgb(
                                            255,
                                            194,
                                            69,
                                            0.2
                                        );
                                    "
                                    type="text"
                                    v-model="address"
                                    maxlength="100"
                                    class="form-control"
                                    name="address"
                                    id="address"
                                    placeholder="Inserisci il tuo indirizzo"
                                    required
                                />
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input
                                    style="
                                        background-color: rgb(
                                            255,
                                            194,
                                            69,
                                            0.2
                                        );
                                    "
                                    type="email"
                                    v-model="email"
                                    maxlength="100"
                                    class="form-control"
                                    name="email"
                                    id="email"
                                    placeholder="Inserisci la tua email"
                                    required
                                />
                            </div>
                        </div>
                        <!-- Form dati ordine -->

                        <div class="card mt-3 card-braintree">
                            <v-braintree
                                authorization="sandbox_8h5ddqng_4sjx493rm6vt8q2c"
                                @success="onSuccess"
                                @error="onError"
                            >
                                <template #button="slotProps">
                                    <v-btn
                                        id="v-btn"
                                        ref="paymentBtnRef"
                                        @click="slotProps.submit"
                                    ></v-btn>
                                </template>
                            </v-braintree>
                            <input
                                class="input-pay"
                                type="submit"
                                @click="beforeBuy()"
                                value="Paga"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="allDone" class="container-checkout">
            <Checkout />
        </div>
    </div>
</template>

<script>
import Axios from "axios";
import Checkout from "./Checkout.vue";
export default {
    name: "Payment",
    data() {
        return {
            name: "",
            lastname: "",
            address: "",
            email: "",
            allDone: false,
            cart: JSON.parse(window.localStorage.getItem("cart")),
        };
    },
    components: {
        Checkout,
    },
    props: {
        cart: {
            type: Array,
            default() {
                return JSON.parse(window.localStorage.getItem("cart"));
            },
        },
    },
    methods: {
        onSuccess(payload) {
            // payload.nonce = this.cart.reduce((total, dish) => total + dish.price * dish.quantity, 0)
            let nonce = payload.nonce;
            let amount = this.cart
                .reduce((total, dish) => total + dish.price * dish.quantity, 0)
                .toFixed(2);
            //axios call per riempire database e poi parte pagamento?
            Axios.post(
                "http://127.0.0.1:8000/api/order/make/payment?token=fake-valid-nonce&amount=" +
                    amount
            ).then((result) => {
                this.allDone = true;
                // console.log(result)
                this.sendForm();
            });
        },
        onError(error) {
            let message = error.message;
            // Whoops, an error has occured while trying to get the nonce
        },
        beforeBuy() {
            let my_btn = document.getElementById("v-btn");
            my_btn.click();
        },
        sendForm() {
            if (this.allDone) {
                let new_cart = [];
                this.cart.forEach((element) => {
                    // console.log(Object.values(element))
                    // console.log(JSON.stringify(element))
                    new_cart.push(JSON.stringify(element));
                });
                let amount = this.cart
                    .reduce(
                        (total, dish) => total + dish.price * dish.quantity,
                        0
                    )
                    .toFixed(2);

                Axios.post("/api/order/save", {
                    name: this.name,
                    lastname: this.lastname,
                    address: this.address,
                    email: this.email,
                    cart: new_cart,
                    amount: amount,
                })
                    .then(
                        (result) =>
                            //console.log(result.data.cart),
                            // console.log(result.data)
                            ""
                    )
                    .catch((error) => console.log(error));
            }
        },
    },
};
</script>

<style lang="scss">
.container-card-riepilogo {
    border-radius: 50px 50px 20px 20px !important;
    .card-riepilogo {
        padding: 2rem;
        h1 {
            font-size: 2rem;
        }
        .riepilogo-ul-list {
            list-style: none;
            li {
                padding: 0.5rem 0;
            }
        }
    }
}
.container-payment-data {
    border-radius: 20px !important;
}
.card-braintree {
    border: 1px solid #38c172;
    background-color: rgb(56, 193, 114, 0.2);
    border-radius: 20px 20px 50px 50px !important;
    padding: 0 2rem;
}
.input-pay {
    margin: 1rem 20rem;
    color: white;
    background-color: #38c172;
    box-shadow: 2px 2px #88888865;
    border-radius: 25px;
    padding: 0.5rem 3rem;
}

@media all and (max-width: 991px) {
    .title-riepilogo {
        font-size: 1.5rem !important;
    }
    .title-payment-data {
        font-size: 1.5rem !important;
    }
    .input-pay {
        margin: 1rem 0;
        padding: 0.5rem 3rem;
    }
}

@media all and (max-width: 767px) {
    .title-riepilogo {
        font-size: 1rem !important;
    }
    .title-payment-data {
        font-size: 1rem !important;
        display: flex;
        align-items: center;
    }
    .input-pay {
        margin: 1rem 0;
        padding: 0.5rem 3rem;
    }
}
</style>
