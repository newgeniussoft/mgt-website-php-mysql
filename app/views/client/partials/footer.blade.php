@import('app/utils/helpers/helper.php')

<!-- Footer Start -->
<footer class="footer mt-5 bg-light text-dark pt-5 pb-3" itemscope itemtype="https://schema.org/LocalBusiness">
            <div class="footer-content">
                <div class="row">
                    <div class="col-md-3 mb-4 mb-md-0 text-center text-md-left">
                        <h4 class="mt-2 mb-1" itemprop="name">Madagascar Green Tours</h4>
                        <p class="small mb-1" itemprop="description">
                            {{ $language == "es" ? $info->short_about_es : $info->short_about }}
                        </p>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h4 class="mt-2 mb-1" itemprop="name">Quick links</h4>
                        <nav aria-label="Footer Navigation">
                            <ul class="list-unstyled mb-0 text-secondary font-inter-regular text-center">
                                <li><a href="{{ route('information') }}" class="text-secondary font-inter-regular">{{ trans('menu.info') }}</a></li>
                                <li><a href="{{ route('sales-conditions') }}" class="text-secondary font-inter-regular">{{ trans('menu.sales-cond') }}</a></li>
                                <li><a href="{{ route('blogs') }}" class="text-secondary font-inter-regular">Blogs</a></li>
                                <li><a href="{{ route('madagascar') }}" class="text-secondary font-inter-regular">Madagascar</a></li>
                             </ul>
                        </nav>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h4 class="mt-2 mb-1" itemprop="name">{{ trans('btn.more') }}</h4>
                        <nav aria-label="Footer Navigation">
                            <ul class="list-unstyled mb-0 text-secondary font-inter-regular text-center">
                                <li>
                                    <a href="{{ route('madagascar-tour-guide') }}" class="text-secondary">
                                        Madagascar tour guide
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('incoming-travel-agency-madagascar') }}" class="text-secondary">
                                        Incoming travel agency Madagascar
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('madagascar-travel-information') }}" class="text-secondary">
                                        Madagascar travel information
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-3 text-center text-secondary">
                        <h4 class="mt-2 mb-1" itemprop="name">Get in touch us</h4>
                        <address class="mb-2 small" itemprop="address" itemscope
                            itemtype="https://schema.org/PostalAddress">
                            <span itemprop="streetAddress">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z">
                                    </path>
                                </svg>{{ $info->address }}</span><br>

                            <span itemprop="telephone">
                                <svg fill="currentColor" width="16" height="16" viewBox="0 0 32 32" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <title>whatsapp</title>
                                    <path
                                        d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507l0 0zM16.062 28.228h-0.005c-0 0-0.001 0-0.001 0-2.319 0-4.489-0.64-6.342-1.753l0.056 0.031-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353h-0zM22.838 18.977c-0.371-0.186-2.197-1.083-2.537-1.208-0.341-0.124-0.589-0.185-0.837 0.187-0.246 0.371-0.958 1.207-1.175 1.455-0.216 0.249-0.434 0.279-0.805 0.094-1.15-0.466-2.138-1.087-2.997-1.852l0.010 0.009c-0.799-0.74-1.484-1.587-2.037-2.521l-0.028-0.052c-0.216-0.371-0.023-0.572 0.162-0.757 0.167-0.166 0.372-0.434 0.557-0.65 0.146-0.179 0.271-0.384 0.366-0.604l0.006-0.017c0.043-0.087 0.068-0.188 0.068-0.296 0-0.131-0.037-0.253-0.101-0.357l0.002 0.003c-0.094-0.186-0.836-2.014-1.145-2.758-0.302-0.724-0.609-0.625-0.836-0.637-0.216-0.010-0.464-0.012-0.712-0.012-0.395 0.010-0.746 0.188-0.988 0.463l-0.001 0.002c-0.802 0.761-1.3 1.834-1.3 3.023 0 0.026 0 0.053 0.001 0.079l-0-0.004c0.131 1.467 0.681 2.784 1.527 3.857l-0.012-0.015c1.604 2.379 3.742 4.282 6.251 5.564l0.094 0.043c0.548 0.248 1.25 0.513 1.968 0.74l0.149 0.041c0.442 0.14 0.951 0.221 1.479 0.221 0.303 0 0.601-0.027 0.889-0.078l-0.031 0.004c1.069-0.223 1.956-0.868 2.497-1.749l0.009-0.017c0.165-0.366 0.261-0.793 0.261-1.242 0-0.185-0.016-0.366-0.047-0.542l0.003 0.019c-0.092-0.155-0.34-0.247-0.712-0.434z">
                                    </path>
                                </svg>
                                {{ $info->whatsapp }}</span><br>
                            <span itemprop="telephone">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.1007 13.359L15.5719 12.8272H15.5719L16.1007 13.359ZM16.5562 12.9062L17.085 13.438H17.085L16.5562 12.9062ZM18.9728 12.5894L18.6146 13.2483L18.9728 12.5894ZM20.8833 13.628L20.5251 14.2869L20.8833 13.628ZM21.4217 16.883L21.9505 17.4148L21.4217 16.883ZM20.0011 18.2954L19.4723 17.7636L20.0011 18.2954ZM18.6763 18.9651L18.7459 19.7119H18.7459L18.6763 18.9651ZM8.81536 14.7266L9.34418 14.1947L8.81536 14.7266ZM4.00289 5.74561L3.2541 5.78816L3.2541 5.78816L4.00289 5.74561ZM10.4775 7.19738L11.0063 7.72922H11.0063L10.4775 7.19738ZM10.6342 4.54348L11.2346 4.09401L10.6342 4.54348ZM9.37326 2.85908L8.77286 3.30855V3.30855L9.37326 2.85908ZM6.26145 2.57483L6.79027 3.10667H6.79027L6.26145 2.57483ZM4.69185 4.13552L4.16303 3.60368H4.16303L4.69185 4.13552ZM12.0631 11.4972L12.5919 10.9654L12.0631 11.4972ZM16.6295 13.8909L17.085 13.438L16.0273 12.3743L15.5719 12.8272L16.6295 13.8909ZM18.6146 13.2483L20.5251 14.2869L21.2415 12.9691L19.331 11.9305L18.6146 13.2483ZM20.8929 16.3511L19.4723 17.7636L20.5299 18.8273L21.9505 17.4148L20.8929 16.3511ZM18.6067 18.2184C17.1568 18.3535 13.4056 18.2331 9.34418 14.1947L8.28654 15.2584C12.7186 19.6653 16.9369 19.8805 18.7459 19.7119L18.6067 18.2184ZM9.34418 14.1947C5.4728 10.3453 4.83151 7.10765 4.75168 5.70305L3.2541 5.78816C3.35456 7.55599 4.14863 11.144 8.28654 15.2584L9.34418 14.1947ZM10.7195 8.01441L11.0063 7.72922L9.9487 6.66555L9.66189 6.95073L10.7195 8.01441ZM11.2346 4.09401L9.97365 2.40961L8.77286 3.30855L10.0338 4.99296L11.2346 4.09401ZM5.73263 2.04299L4.16303 3.60368L5.22067 4.66736L6.79027 3.10667L5.73263 2.04299ZM10.1907 7.48257C9.66189 6.95073 9.66117 6.95144 9.66045 6.95216C9.66021 6.9524 9.65949 6.95313 9.659 6.95362C9.65802 6.95461 9.65702 6.95561 9.65601 6.95664C9.65398 6.95871 9.65188 6.96086 9.64972 6.9631C9.64539 6.96759 9.64081 6.97245 9.63599 6.97769C9.62634 6.98816 9.61575 7.00014 9.60441 7.01367C9.58174 7.04072 9.55605 7.07403 9.52905 7.11388C9.47492 7.19377 9.41594 7.2994 9.36589 7.43224C9.26376 7.70329 9.20901 8.0606 9.27765 8.50305C9.41189 9.36833 10.0078 10.5113 11.5343 12.0291L12.5919 10.9654C11.1634 9.54499 10.8231 8.68059 10.7599 8.27309C10.7298 8.07916 10.761 7.98371 10.7696 7.96111C10.7748 7.94713 10.7773 7.9457 10.7709 7.95525C10.7677 7.95992 10.7624 7.96723 10.7541 7.97708C10.75 7.98201 10.7451 7.98759 10.7394 7.99381C10.7365 7.99692 10.7335 8.00019 10.7301 8.00362C10.7285 8.00534 10.7268 8.00709 10.725 8.00889C10.7241 8.00979 10.7232 8.0107 10.7223 8.01162C10.7219 8.01208 10.7212 8.01278 10.7209 8.01301C10.7202 8.01371 10.7195 8.01441 10.1907 7.48257ZM11.5343 12.0291C13.0613 13.5474 14.2096 14.1383 15.0763 14.2713C15.5192 14.3392 15.8763 14.285 16.1472 14.1841C16.28 14.1346 16.3858 14.0763 16.4658 14.0227C16.5058 13.9959 16.5392 13.9704 16.5663 13.9479C16.5799 13.9367 16.5919 13.9262 16.6024 13.9166C16.6077 13.9118 16.6126 13.9073 16.6171 13.903C16.6194 13.9008 16.6215 13.8987 16.6236 13.8967C16.6246 13.8957 16.6256 13.8947 16.6266 13.8937C16.6271 13.8932 16.6279 13.8925 16.6281 13.8923C16.6288 13.8916 16.6295 13.8909 16.1007 13.359C15.5719 12.8272 15.5726 12.8265 15.5733 12.8258C15.5735 12.8256 15.5742 12.8249 15.5747 12.8244C15.5756 12.8235 15.5765 12.8226 15.5774 12.8217C15.5793 12.82 15.581 12.8183 15.5827 12.8166C15.5862 12.8133 15.5895 12.8103 15.5926 12.8074C15.5988 12.8018 15.6044 12.7969 15.6094 12.7929C15.6192 12.7847 15.6265 12.7795 15.631 12.7764C15.6403 12.7702 15.6384 12.773 15.6236 12.7785C15.5991 12.7876 15.501 12.8189 15.3038 12.7886C14.8905 12.7253 14.02 12.3853 12.5919 10.9654L11.5343 12.0291ZM9.97365 2.40961C8.95434 1.04802 6.94996 0.83257 5.73263 2.04299L6.79027 3.10667C7.32195 2.578 8.26623 2.63181 8.77286 3.30855L9.97365 2.40961ZM4.75168 5.70305C4.73201 5.35694 4.89075 4.9954 5.22067 4.66736L4.16303 3.60368C3.62571 4.13795 3.20329 4.89425 3.2541 5.78816L4.75168 5.70305ZM19.4723 17.7636C19.1975 18.0369 18.9029 18.1908 18.6067 18.2184L18.7459 19.7119C19.4805 19.6434 20.0824 19.2723 20.5299 18.8273L19.4723 17.7636ZM11.0063 7.72922C11.9908 6.7503 12.064 5.2019 11.2346 4.09401L10.0338 4.99295C10.4373 5.53193 10.3773 6.23938 9.9487 6.66555L11.0063 7.72922ZM20.5251 14.2869C21.3429 14.7315 21.4703 15.7769 20.8929 16.3511L21.9505 17.4148C23.2908 16.0821 22.8775 13.8584 21.2415 12.9691L20.5251 14.2869ZM17.085 13.438C17.469 13.0562 18.0871 12.9616 18.6146 13.2483L19.331 11.9305C18.2474 11.3414 16.9026 11.5041 16.0273 12.3743L17.085 13.438Z"
                                        fill="currentColor" />
                                </svg>
                                {{ $info->phone }}</span><br>
                            <a href="mailto:{{ $info->email }}" itemprop="email" class="text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-envelope-check" viewBox="0 0 16 16">
                                    <path
                                        d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z">
                                    </path>
                                    <path
                                        d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z">
                                    </path>
                                </svg>
                                {{ $info->email }}</a>
                        </address>
                        <div class="social-links">
                            <?php foreach($socialMedia as $sMedia): ?>
                            <a href="{{ $sMedia->link }}" aria-label="{{ $sMedia->name }}" rel="noopener"
                                target="_blank"><img src="{{ assets($sMedia->image) }}" alt="{{ $sMedia->name }}" width="24"
                                    height="24"></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row copyright-content py-2">
                <div class="col-12 text-center text-secondary small">
                    <span>Developed by <a href="https://madagascar-green-tours.ct.ws/docs/template/"
                            class="text-secondary">Gino</a>
                        <br>&copy; <span id="year">2025</span> Madagascar Green Tours. All rights reserved. | Eco
                        Tourism
                        Madagascar | Lemur Tours | Sustainable Travel</span> <a
                        href="https://madagascar-green-tours.ct.ws/docs/template/privacy-policy.html"
                        class="text-secondary">Privacy Policy</a>

                </div>
            </div>
            <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
        </footer>
        <!-- Footer End -->
        <div class="floating-chat enter">
    <img class="icon" src="{{ assets('img/logos/whatsapp_white.png') }}" alt="App">
    <div class="chat">
        <div class="user-bar">
            <div class="back">
                <svg onclick="closeElement()" xmlns="http://www.w3.org/2000/svg" id="Bold" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M17.921,1.505a1.5,1.5,0,0,1-.44,1.06L9.809,10.237a2.5,2.5,0,0,0,0,3.536l7.662,7.662a1.5,1.5,0,0,1-2.121,2.121L7.688,15.9a5.506,5.506,0,0,1,0-7.779L15.36.444a1.5,1.5,0,0,1,2.561,1.061Z"></path>
                </svg>
            </div>
            <div class="avatar">
              <img src="{{ assets('img/logos/apple-touch-icon.png') }}" alt="Avatar">
            </div>
            <div class="name">
              <span>Madagascar Green Tours</span>
              <span class="status">online</span>
            </div>
          </div>
        <ul class="messages">
            <li class="self first">hello</li>
            <li class="self second">Can we help you?</li>
            <li class="self second">Scan the following QR code to communicate directly with Madagascar Green Tours 
                        on WhatsApp from your smartphone</li>
            <li class="self thrid">
                <img src="{{ assets('img/images/qrcode.png') }}" width="170" alt="qrcode">
            </li>
        </ul>
        <div class="footer">
            <a href="https://wa.me/261347107100?text=Hello" target="_blank">Open on Whatsapp <i class="fa fa-send"></i>
        </a></div><a href="https://wa.me/261347107100?text=Hello" target="_blank">
    </a></div><a href="https://wa.me/261347107100?text=Hello" target="_blank">
</a></div>
        </main>
        <script src="{{ assets('js/jquery-3.2.1.slim.min.js') }}"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"
            defer></script>
        <script src="{{ assets('js/popper.min.js') }}"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"
            defer></script>
        <script src="{{ assets('js/bootstrap.min.js') }}"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"
            defer></script>


        <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/thumbnail/lg-thumbnail.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/zoom/lg-zoom.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
  AOS.init();
</script>
<?php 
  if(namePage() == 'tours') : ?>    
    
  <!-- amCharts v3 core + map + theme -->
  <script src="https://www.amcharts.com/lib/3/ammap.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <script src="https://www.amcharts.com/lib/3/maps/js/madagascarHigh.js"></script>
    <script>
        function checkIfProvinceExists(provinces, provinceName) {
            for (let i = 0; i < provinces.length; i++) {
                if (provinces[i].title === provinceName) {
                    return true;
                }
            }
            return false;
        }
        // Utility to parse coordinate string to object(s)
        function parseCoordinates(text) {
            const clean = text.trim().replace(/\s+/g, '\n');
            const lines = clean.split('\n').filter(Boolean);
            const coords = lines.map(line => {
                const [lng, lat, alt] = line.trim().split(',').map(Number);
                return { longitude: lng, latitude: lat, altitude: alt };
            });
            return coords.length === 1 ? coords[0] : coords;
        }

        // Recursive XML -> JSON
        function xmlToJson(xml) {
            let obj = {};

            if (xml.nodeType === 1) { // Element
                if (xml.attributes.length > 0) {
                    obj["@attributes"] = {};
                    for (let j = 0; j < xml.attributes.length; j++) {
                        let attribute = xml.attributes.item(j);
                        obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                    }
                }
            } else if (xml.nodeType === 3) { // Text
                const value = xml.nodeValue.trim();
                return value === "" ? undefined : value;
            }

            if (xml.hasChildNodes()) {
                for (let i = 0; i < xml.childNodes.length; i++) {
                    const item = xml.childNodes.item(i);
                    const nodeName = item.nodeName;
                    let value = xmlToJson(item);

                    if (value === undefined || (typeof value === 'string' && value.trim() === "")) continue;

                    // Replace #text with value
                    const key = nodeName === "#text" ? "value" : nodeName;

                    // Special case: if parent is <coordinates>
                    if (xml.nodeName === "coordinates" && key === "value") {
                        value = parseCoordinates(value);
                    }

                    if (obj[key] === undefined) {
                        obj[key] = value;
                    } else {
                        if (!Array.isArray(obj[key])) {
                            obj[key] = [obj[key]];
                        }
                        obj[key].push(value);
                    }
                }
            }

            return obj;
        }
        
        
        const urls = [
            <?php 
            $index = 0;
            foreach(scan_dir("/../../../assets/kml/".currentPage()) as $name): ?>
                <?php  $d = explode("\\", $name);
                $kml = $d[count($d) - 1];
                $index += 1;
                ?>
            "{{ assets('kml/<?= currentPage() ?>/0'. $index.'.kml') }}",
            <?php endforeach; ?>
        ];
        //Now coords is an array of {longitude, latitude}
        var targetSVG = "M9,0C4.029,0,0,4.029,0,9c0,4.971,4.029,9,9,9s9-4.029,9-9C18,4.029,13.971,0,9,0z";
        //var targetSVG = "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"; // Example: location pin

        var provinces = [];
        async function fetchAllData() {
            try {
                const responses = await Promise.all(urls.map(url => fetch(url)));
                const data = await Promise.all(responses.map(res => res.text()));
                var lines = [];
                var ids = [];
                for (let i = 0; i < data.length; i++) {
                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(data[i], "text/xml");
                    const json = xmlToJson(xmlDoc.documentElement);
                  //  console.log(json.Document.Folder.Placemark);
                  
                  var coords = {};
                  var place_mark = {};
                    if ("LineString" in json.Document.Folder.Placemark) {
                        coords = json.Document.Folder.Placemark.LineString.coordinates.value;
                        place_mark = json.Document.Folder.Placemark;
                    } else {
                        coords = json.Document.Folder.Placemark[0].LineString.coordinates.value;
                        place_mark = json.Document.Folder.Placemark[0];
                    }
                    
               
                    
                    const color = json.Document.Color == undefined ? "0f7445" : json.Document.Color.value;
                    var line = {
                        id : 'line_'+i,
                        latitudes: (coords.map(coord => coord.latitude)),
                        longitudes: (coords.map(coord => coord.longitude)),
                        color: "#" + color,
                        thickness: 3
                    }
                    if (place_mark.name.value != undefined) {
                        if (place_mark.name.value.includes("Ligne")) {
                        line.arc = 0.5;
                        line.dashLength = 5;
                        line.thickness = 1.5;
                        ids.push('line_'+i);
                        if (place_mark.name.value.includes("retour") || place_mark.name.value.includes("Toliara Tana")) {
                            line.arc = -0.5;
                        }
                    } else if (place_mark.name.value.includes("Train")) {
                        line.dashLength = 2;
                        line.thickness = 1.5;
                    }
                }
                    lines.push(line);
                    for (let i = 0; i < json.Document.Folder.Placemark.length; i++) {
                        const placemark = json.Document.Folder.Placemark[i];
                        if ('Point' in placemark) {
                            if(!checkIfProvinceExists(provinces, placemark.name.value)) {
                               
                            province = {
                                svgPath: targetSVG,
                                title: placemark.name.value,
                                latitude: placemark.Point.coordinates.value.latitude,
                                longitude: placemark.Point.coordinates.value.longitude,
                                scale: 0.6,
                                label: placemark.name.value,
                                labelPosition: "right",
                                color: "#" + color,
                            }
                            }
                            if (province.title == "Ifaty" || province.title == "Belon'i Tsiribihina"  || province.title == "Morondava" || province.title == "Nosy Be") {
                                province.labelPosition = "left";
                            }
                            if (province.title == "Miandrivazo" || province.title == "Antananarivo" || province.title == "Fianarantsoa"  || province.title == "Manja" || province.title == "Maroantsetra" || province.title == "Tsingy de bemaraha") {
                                province.labelPosition = "top";
                            }
                            if (province.title == "Toliara" || province.title == "Mangabe" || province.title == "Andasibe") {
                                province.labelPosition = "bottom";
                            }
                            provinces.push(province);
                        }
                    }

                }
                // If your map expects arrays of latitudes and longitudes separately:


                var map = AmCharts.makeChart("chartdiv", {
                    type: "map",
                    theme: "light",

                    dataProvider: {
                        map: "madagascarHigh",
                        getAreasFromMap: true,
                        backgroundColor: "#93ce73",

                        images: provinces,
                        lines: lines
                        ,
                    },

                    areasSettings: {
                        autoZoom: true,
                        selectedColor: "#0f7445",
                        outlineColor: "#f4f4f4",
                        outlineThickness: 1,
                        color: "#93ce73",
                    },

                    imagesSettings: {
                        color: "#93ce73",
                        rollOverColor: "#ffcc00",
                        selectedColor: "#ff0000",
                        pauseDuration: 0.5,
                        animationDuration: 20,
                        scale: 1,
                        adjustAnimationSpeed: true
                    },

                    smallMap: {
                        enabled: false,
                    },

                    zoomControl: {
                        panControlEnabled: false,
                        zoomControlEnabled: true,
                    }
                });
                // Add plane after map is built
for(var i = 0; i < ids.length; i++) {
    var plane = {
    svgPath: "M60.1666,38L60.0776,38C60.0776,38 61.254,39.9002 47.2749,40.6107L43.6589,45.9167L44.2443,45.9167C45.1187,45.9167 45.8276,46.6256 45.8276,47.5C45.8276,48.3745 45.1187,49.0834 44.2443,49.0834L41.4144,49.0834L39.673,51.4584L39.8901,51.4584C40.7645,51.4584 41.4734,52.1672 41.4734,53.0417C41.4734,53.9161 40.7645,54.625 39.8901,54.625L37.2359,54.625C35.1849,57.1943 33.2902,59.2888 31.9734,60.1667C31.9734,60.1667 29.2026,60.1667 29.2026,58.5833C29.2026,58.5833 35.6397,46.782 37.9164,40.8418C23.6609,40.9597 23.6609,39.9792 23.6609,39.9792C23.6609,39.9792 20.4943,45.9167 17.3276,45.9167L19.7026,38L19.7917,38L17.4167,30.0833C20.5833,30.0833 23.75,36.0208 23.75,36.0208C23.75,36.0208 23.75,35.0403 38.0055,35.1582C35.7288,29.218 29.2917,17.4167 29.2917,17.4167C29.2917,15.8333 32.0625,15.8334 32.0625,15.8334C33.3792,16.7112 35.2739,18.8058 37.325,21.375L39.9792,21.375C40.8536,21.375 41.5625,22.0839 41.5625,22.9583C41.5625,23.8328 40.8536,24.5417 39.9792,24.5417L39.7621,24.5417L41.5034,26.9167L44.3333,26.9167C45.2078,26.9167 45.9167,27.6255 45.9167,28.5C45.9167,29.3744 45.2078,30.0833 44.3333,30.0833L43.7479,30.0833L47.3639,35.3893C61.343,36.0998 60.1666,38 60.1666,38Z",
    positionOnLine: 0.4,
    animateAlongLine: false,
    lineId: ids[i],
    flipDirection: false,
    loop: true,
    scale: 0.6,
    color: "#0f7445"
  };
  map.dataProvider.images.push(plane);
}
  map.validateData();
//});
    



            } catch (error) {
                console.error("Something went wrong:", error);
            }
            
        }
        
    fetchAllData();
    </script>
    <?php endif; ?>
        <script>
            lightGallery(document.getElementsByClassName('lightgallery')[0], {
                plugins: [lgZoom, lgThumbnail],
                speed: 500
            });
        </script>
        <!-- Optimized JavaScript with defer attribute -->
        <script defer>
             function closeElement() {
                
                var element = $('.floating-chat');
    element.find('.chat').removeClass('enter').hide();
    setTimeout(function() {
        element.find('.chat').removeClass('enter').show();
        $('.floating-chat').removeClass('expand');
    }, 50);
}
            document.addEventListener('DOMContentLoaded', function () {
                // Toggle fadeIn class for navbar collapse
                document.querySelector('.navbar-toggler').addEventListener('click', function () {
                    document.querySelector('.navbar-collapse').classList.toggle('fadeIn');
                });
               

                // Navbar scroll animation
                window.addEventListener('scroll', function () {
                    var scroll = window.scrollY;
                    var navbar = document.getElementById('mainNav');
                    var navbarSupportedContent = document.querySelector("#navbarSupportedContent ul");

                    // Add/remove classes based on scroll position
                    if (scroll > 80) { // Increased threshold for the transition
                        navbar.classList.add('navbar-scrolled');
                        document.body.classList.add('scrolled');
                        navbarSupportedContent.classList.remove('header-styled');
                    } else {
                        navbar.classList.remove('navbar-scrolled');
                        navbarSupportedContent.classList.add('header-styled');
                        document.body.classList.remove('scrolled');
                    }
                });

                // Initialize navbar state on page load
                if (window.scrollY > 100) {
                    document.getElementById('mainNav').classList.add('navbar-scrolled');
                    document.body.classList.add('scrolled');
                }

                // Smooth scrolling for anchor links (excluding dropdown toggles on mobile)
                document.querySelectorAll('a.nav-link').forEach(function (link) {
                    link.addEventListener('click', function (event) {
                        // Skip if this is a dropdown toggle on mobile
                        var isMobile = window.innerWidth < 992;
                        if (this.classList.contains('dropdown-toggle') && isMobile) {
                            return;
                        }
                        if (this.hash !== "") {
                            event.preventDefault();
                            var hash = this.hash;

                            // Smooth scroll to the target
                            document.querySelector(hash).scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    });
                });

                // Initialize dropdown functionality for mobile devices
                var isMobile = false;
                if (window.matchMedia("(max-width: 991.98px)").matches) {
                    isMobile = true;
                }

                // Handle window resize events
                $(window).resize(function () {
                    if (window.matchMedia("(max-width: 991.98px)").matches) {
                        isMobile = true;
                    } else {
                        isMobile = false;
                        // Close any open dropdowns when switching to desktop
                        $('.dropdown-menu').removeClass('show');
                    }
                });

                // For mobile: toggle dropdown on click
                $('.dropdown-toggle').on('click', function (e) {
                    if (isMobile) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).next('.dropdown-menu').toggleClass('show');
                    }
                });

                // Close dropdown when clicking outside
                $(document).on('click', function (e) {
                    if (isMobile && !$(e.target).closest('.dropdown').length) {
                        $('.dropdown-menu').removeClass('show');
                    }
                });

                var element = $('.floating-chat');
var myStorage = localStorage;

if (!myStorage.getItem('chatID')) {
    myStorage.setItem('chatID', createUUID());
}

setTimeout(function() {
    element.addClass('enter');
}, 1000);

element.click(openElement);

function openElement() {
    var messages = element.find('.messages');
    var textInput = element.find('.text-box');
    element.find('>i').hide();
    element.addClass('expand');
    element.find('.chat').addClass('enter');
    var strLength = textInput.val().length * 2;
    textInput.keydown(onMetaAndEnter).prop("disabled", false).focus();
    element.off('click', openElement);
    element.find('.user-bar div').click(closeElement);
    element.find('#sendMessage').click(sendNewMessage);
    messages.scrollTop(messages.prop("scrollHeight"));
}

function closeElement() {
    element.find('.chat').removeClass('enter').hide();
    setTimeout(function() {
        element.find('.chat').removeClass('enter').show();
        $('.floating-chat').removeClass('expand');
    }, 50);
    element.click(openElement);
}

function createUUID() {
    // http://www.ietf.org/rfc/rfc4122.txt
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    var uuid = s.join("");
    return uuid;
}

function sendNewMessage() {
    var userInput = $('.text-box');
    var newMessage = userInput.html().replace(/\<div\>|\<br.*?\>/ig, '\n').replace(/\<\/div\>/g, '').trim().replace(/\n/g, '<br>');

    if (!newMessage) return;

    var messagesContainer = $('.messages');

    messagesContainer.append([
        '<li class="self">',
        newMessage,
        '</li>'
    ].join(''));

    // clean out old message
    userInput.html('');
    // focus on input
    userInput.focus();

    messagesContainer.finish().animate({
        scrollTop: messagesContainer.prop("scrollHeight")
    }, 250);
}

function onMetaAndEnter(event) {
    if ((event.metaKey || event.ctrlKey) && event.keyCode == 13) {
        sendNewMessage();
    }
}


            });

        </script>
        
<script>
/*
    //<![CDATA[
var show_msg = '1';

if (show_msg !== '0') {
  var options = {view_src: "View Source is disabled!", inspect_elem: "Inspect Element is disabled!", right_click: "Right click is disabled!", copy_cut_paste_content: "Cut/Copy/Paste is disabled!", image_drop: "Image Drag-n-Drop is disabled!" }
} else {
  var options = '';
}

    function nocontextmenu(e) { return false; }
    document.oncontextmenu = nocontextmenu;
    document.ondragstart = function() { return false;}

document.onmousedown = function (event) {
  event = (event || window.event);
  if (event.keyCode === 123) {
    if (show_msg !== '0') {show_toast('inspect_elem');}
    return false;
  }
}
document.onkeydown = function (event) {
  event = (event || window.event);
  //alert(event.keyCode);   return false;
  if (event.keyCode === 123 ||
      event.ctrlKey && event.shiftKey && event.keyCode === 73 ||
      event.ctrlKey && event.shiftKey && event.keyCode === 75) {
    if (show_msg !== '0') {show_toast('inspect_elem');}
    return false;
  }
  if (event.ctrlKey && event.keyCode === 85) {
    if (show_msg !== '0') {show_toast('view_src');}
    return false;
  }
}
function addMultiEventListener(element, eventNames, listener) {
  var events = eventNames.split(' ');
  for (var i = 0, iLen = events.length; i < iLen; i++) {
    element.addEventListener(events[i], function (e) {
      e.preventDefault();
      if (show_msg !== '0') {
        show_toast(listener);
      }
    });
  }
}
addMultiEventListener(document, 'contextmenu', 'right_click');
addMultiEventListener(document, 'cut copy paste print', 'copy_cut_paste_content');
addMultiEventListener(document, 'drag drop', 'image_drop');
function show_toast(text) {
  var x = document.getElementById("amm_drcfw_toast_msg");
  x.innerHTML = eval('options.' + text);
  x.className = "show";
  setTimeout(function () {
    x.className = x.className.replace("show", "")
  }, 3000);
}*/
//]]>

</script>
<style type="text/css">
/*
body * :not(input):not(textarea){
    user-select:none !important; 
    -webkit-touch-callout: none !important;  
    -webkit-user-select: none !important; 
    -moz-user-select:none !important; 
    -khtml-user-select:none !important; 
    -ms-user-select: none !important;
    }
    #amm_drcfw_toast_msg{
        visibility:hidden;
        min-width:250px;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        margin-left:-125px;
        background-color:#333;
        color:#fff;
        text-align:center;
        border-radius:25px;
        padding:4px;
        position:fixed;
        z-index:999;
        left:50%;
        bottom:30px;
        font-size:17px
    }
    #amm_drcfw_toast_msg.show{
        visibility:visible;
        -webkit-animation:fadein .5s,fadeout .5s 2.5s;
        animation:fadein .5s,fadeout .5s 2.5s
    }
    @-webkit-keyframes fadein{
        from{
            bottom:0;
            opacity:0
        }
        to{
            bottom:30px;
            opacity:1
        }
    }
    @keyframes fadein{
        from{
            bottom:0;
            opacity:0
        }
        to{
            bottom:30px;
            opacity:1
        }
    }
    @-webkit-keyframes fadeout{
        from{
            bottom:30px;
            opacity:1
        }
        to{
            bottom:0;
            opacity:0
        }
    }
    @keyframes fadeout{
        from{
            bottom:30px;
            opacity:1
        }
        to{
            bottom:0;
            opacity:0
        }
    }*/
</style>




<div id="amm_drcfw_toast_msg"></div>
    
</body>
</html>