<div class="container">
  <h1>What is happening on this page?</h1>
  <div class="row">
    <div class="col-lg-6">
      <ol>
        <li>The browser requests static contents to the view.</li>
        <li>The view receives request from the browser and responds with static contents to the browser.</li>
        <li>The browser renders static contents from the view and instructs the controller to request dynamic contents.</li>
        <li>The controller receives instructions from the browser and requests dynamic contents to the model.</li>
        <li>The model receives request from the controller and responds dynamic contents to the controller.</li>
        <li>The controller renders dynamic contents from the model.</li>
      </ol>
    </div>
    <div class="col-lg-6">
      <svg width="260px" height="260px" style="background-color:#ddd;">
        <defs>
          <polygon points="0,0 6,3 0,6 10,3" id="m_tmpl2"/>
          <marker id="m_o1" markerWidth="10" markerHeight="10" viewBox="0 0 10 10" refX="10" refY="3" orient="auto">
            <use xlink:href="#m_tmpl2" fill="black"/>
          </marker>
        </defs>
        <g stroke="black">
          <path d="M40,30 l0,220" />
          <path d="M100,30 l0,220" />
          <path d="M160,30 l0,220" />
          <path d="M220,30 l0,220" />
        </g>
        <g stroke="black" marker-end="url(#m_o1)">
          <path d="M40,50 l120,40" />
          <path d="M160,90 l-120,40" />
          <path d="M40,130 l60,20" />
          <path d="M100,150 l120,40" />
          <path d="M220,190 l-120,40" />
        </g>
        <g font-size="12" fill="black" text-anchor="middle">
          <text x="40" y="20">Browser</text>
          <text x="100" y="20">Controller</text>
          <text x="160" y="20">View</text>
          <text x="220" y="20">Model</text>
          <text x="30" y="50">1.</text>
          <text x="170" y="90">2.</text>
          <text x="30" y="130">3.</text>
          <text x="110" y="150">4.</text>
          <text x="230" y="190">5.</text>
          <text x="90" y="230">6.</text>
        </g>
      </svg>
    </div>
  </div>
  <hr>
  <h2>Dynamic contents from the model</h2>
  <pre id="result">
  </pre>
  <div class="alert alert-info">If a database exception occurs, set the appropriate values in <i>./application/config.php</i>.</div>
</div>
