var API, Controllers = {}, Services = {};

API = function () {
    var request = function (type, endpoint, data, callback, rootUrl) {
        var url = (rootUrl !== undefined) ? rootUrl + endpoint : config.server + endpoint;
        $.ajax({
            type: type,
            url: url,
            data: data,
            beforeSend: function (request) {
                request.setRequestHeader("X-CSRF-TOKEN", config.token);
            },
            complete: function (data) {
                callback(JSON.parse(data.responseText));
            }
        });
    };

    var users = function (action, endpoint, data, callback, rootUrl) {
        var url = "users/" + endpoint;
        request(action, url, data, callback, rootUrl);
    };

    var stores = function (action, endpoint, data, callback, rootUrl) {
        var url = "stores/" + endpoint;
        request(action, url, data, callback, rootUrl);
    };

    var orders = function (action, endpoint, data, callback, rootUrl) {
        var url = "orders/" + endpoint;
        request(action, url, data, callback, rootUrl);
    };

    var apis = function (action, endpoint, data, callback, rootUrl) {
        var url = endpoint;
        request(action, url, data, callback, rootUrl);
    };

    return {
        login: function (email, password, callback) {
            users("POST", "authenticate", { email: email, password: password }, callback, "/");
        },
        join: function (member, callback) {
            users("POST", "join", member, callback, "/");
        },
        resetPassword: function (payload, callback) {
            users("POST", "reset-password", payload, callback);
        },
        passwordCode: function (email, callback) {
            users("GET", "password-code/" + email, {}, callback);
        },
        me: function (callback) {
            users("GET", "me", {}, callback, "/");
        },
        registered: function (email, callback) {
            users("GET", "registered/" + email, {}, callback);
        },
        product: function (handle, callback) {
            stores("GET", "product/" + handle, {}, callback);
        },
        cart: function (productIds, callback) {
            stores("GET", "cart/" + productIds, {}, callback);
        },
        getDiscountForCode: function (code, callback) {
            stores("GET", "discount/" + code, {}, callback);
        },
        categories: function (callback) {
            stores("GET", "categories/", {}, callback);
        },
        search: function (q, categoryId, callback) {
            if (categoryId !== '0') {
                stores("GET", "search/" + q + "?cid=" + categoryId, {}, callback);
            } else {
                stores("GET", "search/" + q, {}, callback);
            }

        },
        advancedSearch: function (params, callback) {
            stores("GET", "advanced-search", params, callback);
        },
        placeOrder: function (payload, callback) {
            orders("POST", "place", payload, callback, "/");
        },

        postalCodeLookup: function (postalCode, callback) {
            apis("GET", "bring-postal-code/" + postalCode, {}, callback);
        },
        pickupPoints: function (postalCode, callback) {
            apis("GET", "cargonizer/pickup-points/" + postalCode, {}, callback);
        },

        contact: function (data, callback) {
            apis("POST", "contact", data, callback);
        },
        bookTechnicalService: function (data, callback) {
            apis("POST", "technical-service", data, callback);
        }
    };
};

Controllers.Login = function () {
    var form = $("#login-form");
    var onLoginComplete = function (response) {
        form.toggleClass("loading");
        if (response.success) {
            var user = response.data.user;
            $("#user-fullname").text(user.first_name + " " + user.last_name);
            form.toggleClass("success");
            setTimeout(function () { window.location = response.data.redirect }, 1500);
        } else {
            form.toggleClass("failed");
            setTimeout(function () { form.removeClass("failed"); }, 3000);
        }
    };
    var login = function (e) {
        e.preventDefault();
        if (form.valid()) {
            form.toggleClass("loading");
            Barefilter.API.login($("#email").val(), $("#password").val(), onLoginComplete);
        }
    };
    var init = function () {
        form.validate();
        $("#login-button").click(login);
    };
    init();
};

Controllers.Join = function () {
    var form = $("#join-form");
    var onRegistrationComplete = function (response) {
        form.toggleClass("loading");
        if (response.success) {
            var user = response.data.user;
            $("#user-fullname").text(user.first_name + " " + user.last_name);
            form.toggleClass("success");
            setTimeout(function () { window.location = response.data.redirect }, 1500);
        } else {
            form.toggleClass("failed");
        }
    };
    var join = function (e) {
        e.preventDefault();
        if (form.valid()) {
            if ($("#accept-tos").prop('checked')) {
                form.toggleClass("loading");
                Barefilter.API.join({
                    first_name: $("#first-name").val(),
                    last_name: $("#last-name").val(),
                    email: $("#email").val(),
                    phone: $("#phone").val(),
                    password: $("#password").val()
                }, onRegistrationComplete);
            } else {

            }
            /**/
        }
    };
    var init = function () {
        form.validate({
            rules: {
                password: "required",
                repeat_password: {
                    equalTo: "#password"
                }
            }
        });
        $("#join-button").click(join);
    };
    init();
};

Controllers.ProductGallery = function (id) {
    var el = ".product-gallery" + id, navigator = el + " .page-control";
    var content, pageWidth, current, itemsTotal;

    var navigateBack = function () {
        if (current > 0) {
            current--;
            if (current != 0) {
                $(navigator + " .sl-next").removeClass("disabled");
            } else if (current == 0) {
                $(navigator + " .sl-next").removeClass("disabled");
                $(navigator + " .sl-prev").addClass("disabled");
            }
            navigate();
        }
    };
    var navigateNext = function () {
        if (current < itemsTotal - 1) {
            current++;
            if (current >= 0) {
                $(navigator + " .sl-prev").removeClass("disabled");
                if (current == itemsTotal - 1) {
                    $(navigator + " .sl-next").addClass("disabled");
                }
            }
            navigate();
        } else {
            current = -1;
        }
    };
    var onNavigate = function (e) {
        $(navigator + " ul > li").removeClass("current");
        current = $(e.target).closest("li").data("index");
        $(e.target).closest("li").addClass("current");
    }

    var navigate = function () {
        $(navigator + " ul > li").removeClass("current");
        $(navigator + ' ul li[data-index="' + current + '"]').addClass("current");
        content.css("left", -current * pageWidth);
    };

    var initNavigation = function () {
        current = 0;
        var html = "";
        for (var i = 0; i < itemsTotal; i++) {
            html += (i === 0) ? '<li class="current" data-index="0"></li>' : '<li data-index="' + i + '"></li>';
        }
        $(navigator + " ul").html(html);
        $(navigator + " ul > li").click(onNavigate);
    };

    var init = function () {
        var itemsPerPage, itemSize;
        pageWidth = $(el).outerWidth();
        content = $(el + " .slides");
        current = 0;
        itemsTotal = $(el + " .slide").length;
        var next = $(el + " .sl-next").click(navigateNext);
        var prev = $(el + " .sl-prev").click(navigateBack);
        $(el + " .slide").css("width", pageWidth);
        content.css('width', itemSize * itemsTotal);
        initNavigation();
    };

    this.reset = function () {
        content.css("left", 0);
    };
    init();
};

Controllers.Product = function () {
    var modal = ".product-summary";
    var addToCart = ".product-page-add-to-cart";
    var productInPreview;


    var updateTotal = function () {
        var price = $(addToCart).data("price");
        var amount = $(modal + " #product-page-filter-amount").val();
        var option = $(modal + ' #product-page-filter-subscription').find(":selected");
        amount = (amount <= 0) ? 1 : amount;
        var cost = price * amount;
        var discount = Math.round(cost * option.data("discount") / 100);
        cost = cost - discount;
        $(modal + " #price").text(cost);
        $(modal + " #product-page-filter-amount").val(amount);

        if (discount > 0) {
            $(modal + " #discount").show();
            $(modal + " #discount span").text(discount);
        } else {
            $(modal + " #discount").hide();
        }
    };


    var init = function () {
        new Controllers.ProductGallery("#product-page-gallery");
        $(modal + " #discount").hide();
        $(modal + " #product-page-filter-subscription").change(updateTotal);
        $(modal + " #product-page-filter-amount").change(updateTotal);
    };

    init();
};

Controllers.ProductPreview = function () {
    var modal = "#product-quick-view-modal";
    var button = ".product-preview-button";
    var addToCart = ".product-preview-modal-add-to-cart";
    var description = ".product-description";
    var productInPreview;
    var gallery;

    var renderResults = function (product) {
        var url = (product.images !== undefined && product.images.length > 0) ? product.images[0].url : "";
        productInPreview = product;
        $(addToCart).data("id", product.id);
        $(addToCart).data("name", product.name);
        $(addToCart).data("category", product.sku);

        $(description).data("width", product.width);
        $(description).data("height", product.height);
        $(description).data("length", product.length);

        $(addToCart).data("image", url);
        $(addToCart).data("price", product.price);
        $(modal + " #product-image").attr("src", url)
        $(modal + " #name").text(product.name);
        $(modal + " #sku").text(product.sku);
        $(modal + " #short-description").text(product.short_description);
        $(modal + " #price").text(product.price);

        $(modal + " #Dimensions").text(product.width + 'x' + product.height + 'x' + product.length);

        $(modal + " #discount").hide();
        $(modal + " #description").html(product.description);
        $(modal + " #product-preview-modal-filter-amount").val(1);

        if (product.is_Stock == "1") {
            $(modal + " #InStock").show();
            $(modal + " #OutStock").hide();
        } else {
            $(modal + " #InStock").hide();
            $(modal + " #OutStock").show();
        }

    };

    var updateTotal = function () {
        var amount = $(modal + " #product-preview-modal-filter-amount").val();
        var option = $(modal + ' #product-preview-modal-filter-subscription').find(":selected");
        amount = (amount <= 0) ? 1 : amount;
        var cost = productInPreview.price * amount;
        var discount = Math.round(cost * option.data("discount") / 100);
        cost = cost - discount;
        $(modal + " #price").text(cost);
        $(modal + " #product-preview-modal-filter-amount").val(amount);

        if (discount > 0) {
            $(modal + " #discount").show();
            $(modal + " #discount span").text(discount);
        } else {
            $(modal + " #discount").hide();
        }
    };

    var initSlider = function () {
        var html = "";
        productInPreview.images.forEach(function (image) {
            html += '<div class="slide"><img id="product-image" src="' + image.url + '" alt="placeholder" style="width: 100%; object-fit: contain;" /></div>'
        });
        $("#product-quick-view-gallery .slides").html(html);
        if (gallery != undefined) {
            gallery.reset();
        }
        gallery = new Controllers.ProductGallery("#product-quick-view-gallery");
    };

    var onResponse = function (response) {
        if (response.success) {

            renderResults(response.data);
            $(modal).toggleClass("loading");
            setTimeout(initSlider, 100);
        }
    };
    var preview = function (e) {
        $(modal).modal("show");
        $(modal).toggleClass("loading");
        var handle = $(e.target).closest(button).data("handle");
        Barefilter.API.product(handle, onResponse);
        e.preventDefault();
    };
    var init = function () {
        $(button).click(preview);
        $(modal + " #product-preview-modal-filter-subscription").change(updateTotal);
        $(modal + " #product-preview-modal-filter-amount").change(updateTotal);
    };

    init();
};


Controllers.ProductAddedToCart = function () {
    var modal = "#product-added-to-cart";
    var product = {}, cart = {};
    this.setCartInstance = function (c) {
        cart = c;
    };
    this.show = function (p) {
        product = p;
        $(modal).modal("show");
        $(modal + " #discount").hide();
        $(modal + " #image").attr("src", product.image);
        $(modal + " #name").text(product.name);
        $(modal + " #category").text(product.category);
        $(modal + " #price").text(product.price);
        $(modal + " #filter-subscription").val(product.subscription_id);
        $(modal + " #filter-amount").val(product.total);
    };

    var updateTotal = function () {
        var amount = $(modal + " #filter-amount").val();
        var option = $(modal + ' #filter-subscription').find(":selected");
        amount = (amount <= 0) ? 1 : amount;
        var cost = product.price * amount;
        var discount = Math.round(cost * option.data("discount") / 100);
        cost = cost - discount;
        $(modal + " #price").text(cost);
        $(modal + " #filter-amount").val(amount);

        if (discount > 0) {
            $(modal + " #discount").show();
            $(modal + " #discount span").text(discount);
        } else {
            $(modal + " #discount").hide();
        }

        cart.update(product.id, { id: option.data("id"), discount: option.data("discount") }, amount);
    };

    var init = function () {
        $(modal + " #filter-subscription").change(updateTotal);
        $(modal + " #filter-amount").change(updateTotal);
        $(modal + " #discount").hide();
    };

    init();
};

Controllers.Cart = function (modal) {
    var cart, successModal = modal;

    this.getIds = function () {
        var ids = [];
        cart.forEach(function (item) {
            ids.push(item.id);
        });
        return ids.join(",");
    };
    this.getItems = function () {
        return cart;
    };
    this.add = function (product, subscription) {
        var p = findProduct(product);
        if (p === null) {
            var cost = product.price * product.amount;
            cost = cost - Math.round(cost * subscription.discount / 100);
            cart.push({
                id: product.id,
                name: product.name,
                category: product.category,
                image: product.image,
                subscription_id: subscription.id,
                price: product.price,
                total: product.amount,
                cost: cost
            });
            window.localStorage.setItem("cart", JSON.stringify(cart));
            successModal.show(cart[cart.length - 1]);
            updatePreview();
        } else {
            p.total += product.amount;
            successModal.show(p);
            this.update(p.id, subscription, p.total);
        }
    };
    this.remove = function (id) {
        var idx = -1;
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id === id) {
                idx = i;
                break;
            }
        }
        cart.splice(idx, 1);
        window.localStorage.setItem("cart", JSON.stringify(cart));
        updatePreview();
    };
    this.clear = function () {
        cart = [];
        window.localStorage.setItem("cart", JSON.stringify(cart));
        updatePreview();
    };
    this.update = function (id, subscription, total) {
        var cost;
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id === id) {
                cost = cart[i].price * total;
                cost = cost - Math.round(cost * subscription.discount / 100);
                cart[i].subscription_id = subscription.id;
                cart[i].total = total;
                cart[i].cost = cost;
                break;
            }
        }
        window.localStorage.setItem("cart", JSON.stringify(cart));
        updatePreview();
    };

    var findProduct = function (product) {
        var p = null;
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id === product.id) {
                p = cart[i];
                break;
            }
        }
        return p;
    };
    var updatePreview = function () {
        var totalCost = 0;
        cart.forEach(function (item) {
            totalCost += item.cost;
            $(".product-teaser#product-" + item.id).addClass("in-cart");
        });
        $("#cart #items-in-cart").text(cart.length);
        $("#cart #total-cost").text(totalCost);
        $("#cart #total-item").text(cart.length);
    };
    var init = function () {
        var json = window.localStorage.getItem("cart");
        cart = (json !== null) ? JSON.parse(json) : [];

        updatePreview();
    };
    init();
};

Controllers.Contact = function () {
    var form = "#contact-form";

    var getData = function () {
        return {
            first_name: $(form + " #first_name").val(),
            last_name: $(form + " #last_name").val(),
            phone: $(form + " #phone").val(),
            email: $(form + " #email").val(),
            subject: $(form + " #subject").val(),
            message: $(form + " #message").val()
        }
    };

    var send = function (e) {
        e.preventDefault();
        if ($(form).valid()) {
            $(form + ' #send-message').hide(); 
            return;
            Barefilter.API.contact(getData(), function (response) {
                if (response.success) {
                    $(".success-msj").style('display: block;');
                } else {

                }
            });
        }
    };

    var init = function () {
        $(form).validate();
        $(form + ' #send-message').click(send);
    };
    init();
};

Controllers.TechnicalService = function () {
    var form = "#technical-service-form";

    var getData = function () {
        return {
            subscription: $(form + " #subscription option:selected").val(),
            filterbytte: $(form + " #filterbytte option:selected").val(),
            description: $(form + " #description").val(),
            contact: {
                first_name: $(form + " #first_name").val(),
                last_name: $(form + " #last_name").val(),
                phone: $(form + " #phone").val(),
                email: $(form + " #email").val()
            },
            location: {
                street_address: $(form + " #street_address").val(),
                postal_code: $(form + " #postal_code").val(),
                city: $(form + " #city").val(),
            }
        }
    };

    var send = function (e) {
        e.preventDefault();
        if ($(form).valid()) {
            $(form + ' #book-service').hide();
            Barefilter.API.bookTechnicalService(getData(), function (response) {
                if (response.success) {
                    $("#filter-service-modal").modal('hide');
                    $("#service-request-complete-modal").modal('show');
                }
            });
        }
    };

    var init = function () {
        $(form).validate();
        $(form + ' #book-service').click(send);
    };
    init();
};

Controllers.ShoppingCart = function () {
    var cart, quick = ".quick-add-to-cart-button", product = ".product-page-add-to-cart", preview = ".product-preview-modal-add-to-cart";

    this.getCartInstance = function () {
        return cart;
    };

    var getProductObject = function (el, amount) {
        return {
            id: el.data("id"),
            name: el.data("name"),
            category: el.data("category"),
            image: el.data("image"),
            price: el.data("price"),
            amount: amount
        }
    }
    var quickAddToCart = function (e) {
        e.preventDefault();
        var el = $(e.target).closest(quick);
        var teaser = $(".product-teaser#product-" + el.data("id"));
        teaser.addClass("adding-to-cart");
        setTimeout(function () {
            teaser.removeClass("adding-to-cart");
            teaser.addClass("in-cart");
            cart.add(getProductObject(el, 1), { id: 1, discount: 0 });
        }, 500);

    };
    var addToCartFromProductPage = function (e) {
        var el = $(e.target).closest(product);
        var option = $('#product-page-filter-subscription').find(":selected");
        var subscription = { id: option.data("id"), discount: option.data("discount") };
        cart.add(getProductObject(el, parseInt($("#product-page-filter-amount").val())), subscription);
    };
    var addToCartFromPreviewModal = function (e) {
        var el = $(e.target).closest(preview);
        var option = $('#product-preview-modal-filter-subscription').find(":selected");
        var subscription = { id: option.data("id"), discount: option.data("discount") };
        cart.add(getProductObject(el, parseInt($("#product-preview-modal-filter-amount").val())), subscription);
    };

    var init = function () {
        $(quick).click(quickAddToCart);
        $(product).click(addToCartFromProductPage);
        $(preview).click(addToCartFromPreviewModal);
        var successModal = new Controllers.ProductAddedToCart();
        cart = new Controllers.Cart(successModal);
        successModal.setCartInstance(cart);
    };
    init();
};

Controllers.Store = function () {
    var shoppingCart, product, productPreview, technicalService;
    this.getCartInstance = function () {
        return shoppingCart.getCartInstance();
    };
    var init = function () {
        shoppingCart = new Controllers.ShoppingCart();
        product = new Controllers.Product();
        productPreview = new Controllers.ProductPreview();
        technicalService = new Controllers.TechnicalService();
    };
    init();
};

Controllers.Payment = function () {
    var init = function () {
        new Controllers.Cart().clear();
    };
    init();
};

var Barefilter = {
    API: new API(),
    Controllers: Controllers
};
