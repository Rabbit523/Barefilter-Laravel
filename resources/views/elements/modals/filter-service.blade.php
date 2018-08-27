<div class="modal fade" id="filter-service-modal" tabindex="-1" role="dialog">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <div class="row" >
                        <form id="technical-service-form">
                            <div class="col-lg-12 col-md-12 transfer-description">
                                <div class="transfer-title">
                                    <h1>Borettslag &amp; sameie</h1>
                                    <h3>Dersom dere har et sameie/borettslag lønner det seg å be om pris på filter<br><br> Her kan det være mye penger å spare.</h3>
                                </div>
                                <hr>
                                <section id="select-information">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h2>Antall leveringer</h2>
                                            <p>Velg antall leveringer pr. år.</p>

                                            <select name="select1" id="subscription">
                                                <option selected value="Engangskjøp">Engangskjøp</option>
                                                <option value="Engang i året">Engang i året </option>
                                                <option value="To ganger i året">To ganger i året</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-6">

                                            <h2>Ønsker du filterbytte?</h2>
                                            <p>Vi hjelper deg med å bytte filter</p>

                                            <select name="select2" id="filterbytte">
                                                <option selected value="Nei, takk">Nei, takk</option>
                                                <option value="Ja, takk">Ja, takk </option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <hr>
                                <section id="contact-information">
                                    <h2>Kontaktinformasjon</h2>
                                    <p>Fyll ut din kontaktinformasjon</p>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="text" name="first_name" id="first_name" placeholder="Fornavn" required>
                                            <input type="text" name="phone" id="phone" placeholder="Telefonnummer" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" name="last_name" id="last_name" placeholder="Etternavn" required>
                                            <input type="text" name="email" id="email" placeholder="E-post" required>
                                        </div>
                                    </div>
                                </section>
                                <hr>
                                <section id="address-information">
                                    <h2>Borettslagets adresse</h2>
                                    <p>Skriv inn borettslagets adresse</p>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" name="street_address" id="street_address" placeholder="Adresse" required>

                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" name="postal_code" id="postal_code" placeholder="Postnummer" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" name="city" id="city" placeholder="Poststed" required>
                                        </div>
                                    </div>


                                </section>
                                <hr>
                                <section>
                                    <h2>Beskrivelse</h2>
                                    <p>Skriv en detaljert beskrivelse på hva vi kan hjelpe deg med.</p>
                                    <textarea class="form-control" name="description" id="description" rows="5" placeholder="Skriv melding" required></textarea>
                                </section>
                                <hr>

                                <button class="barefilter-btn dark-blue-full" id="book-service">SEND HENVENDELSE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
