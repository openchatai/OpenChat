// Utility functions
const hexToRGB = (h) => {
  let r = 0;
  let g = 0;
  let b = 0;
  if (h.length === 4) {
    r = `0x${h[1]}${h[1]}`;
    g = `0x${h[2]}${h[2]}`;
    b = `0x${h[3]}${h[3]}`;
  } else if (h.length === 7) {
    r = `0x${h[1]}${h[2]}`;
    g = `0x${h[3]}${h[4]}`;
    b = `0x${h[5]}${h[6]}`;
  }
  return `${+r},${+g},${+b}`;
};

const formatValue = (value) => Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  maximumSignificantDigits: 3,
  notation: 'compact',
}).format(value);

// Define Chart.js default settings
Chart.defaults.font.family = '"Inter", sans-serif';
Chart.defaults.font.weight = '500';
Chart.defaults.color = '#94a3b8';
Chart.defaults.scale.grid.color = '#f1f5f9';
Chart.defaults.plugins.tooltip.titleColor = '#1e293b';
Chart.defaults.plugins.tooltip.bodyColor = '#1e293b';
Chart.defaults.plugins.tooltip.backgroundColor = '#ffffff';
Chart.defaults.plugins.tooltip.borderWidth = 1;
Chart.defaults.plugins.tooltip.borderColor = '#e2e8f0';
Chart.defaults.plugins.tooltip.displayColors = false;
Chart.defaults.plugins.tooltip.mode = 'nearest';
Chart.defaults.plugins.tooltip.intersect = false;
Chart.defaults.plugins.tooltip.position = 'nearest';
Chart.defaults.plugins.tooltip.caretSize = 0;
Chart.defaults.plugins.tooltip.caretPadding = 20;
Chart.defaults.plugins.tooltip.cornerRadius = 4;
Chart.defaults.plugins.tooltip.padding = 8;

// Init #fintech-01 chart
const fintechCard01 = () => {
  const ctx = document.getElementById('fintech-card-01');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '12-01-2020', '01-01-2021', '02-01-2021',
        '03-01-2021', '04-01-2021', '05-01-2021',
        '06-01-2021', '07-01-2021', '08-01-2021',
        '09-01-2021', '10-01-2021', '11-01-2021',
        '12-01-2021', '01-01-2022', '02-01-2022',
        '03-01-2022', '04-01-2022', '05-01-2022',
        '06-01-2022', '07-01-2022', '08-01-2022',
        '09-01-2022', '10-01-2022', '11-01-2022',
        '12-01-2022', '01-01-2023', '02-01-2023',
        '03-01-2023', '04-01-2023', '05-01-2023',
        '06-01-2023', '07-01-2023', '08-01-2023',
        '09-01-2023', '10-01-2023', '11-01-2023',
        '12-01-2023', '01-01-2024', '02-01-2024',
        '03-01-2024', '04-01-2024',
      ],
      datasets: [
        // Indigo line
        {
          label: 'Mosaic Portfolio',
          data: [
            0, 2.5, 2.5, 4, 2.5, 3.8, 5, 9, 7.5, 11,
            14, 15, 17, 15, 14, 9, 15, 26, 16, 18,
            15, 20, 18, 19, 19, 24, 29, 26, 39, 27,
            35, 32, 29, 35, 36, 34, 39, 36, 41, 41,
            48,
          ],
          borderColor: '#6366f1',
          fill: true,
          backgroundColor: `rgba(${hexToRGB('#3b82f6')}, 0.08)`,
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#6366f1',
          clip: 20,
        },
        // Yellow line
        {
          label: 'Expected Return',
          data: [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
            10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
            20, 21, 22, 23, 24, 25, 26, 27, 28, 29,
            30, 31, 32, 33, 34, 35, 36, 37, 38, 39,
            40,
          ],
          borderColor: '#fbbf24',
          borderDash: [4, 4],
          fill: false,
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#fbbf24',
          clip: 20,
        },
        // gray line
        {
          label: 'Competitors',
          data: [
            0.7, 3.5, 4.5, 3.5, 4.2, 4.6, 6, 7, 6, 6,
            11, 13, 14, 18, 17, 15, 13, 16, 20, 21,
            24, 22, 20, 22, 25, 18, 21, 23, 24, 32,
            28, 29, 35, 37, 42, 32, 32, 33, 33, 37,
            32,
          ],
          borderColor: '#cbd5e1',
          fill: false,
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#cbd5e1',
          clip: 20,
        },
      ],
    },
    options: {
      layout: {
        padding: 20,
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
          },
          ticks: {
            maxTicksLimit: 7,
            callback: (value) => `${value}%`,
          },
        },
        x: {
          type: 'time',
          time: {
            parser: 'MM-DD-YYYY',
            unit: 'month',
            displayFormats: {
              month: 'MMM YY',
            },
          },
          grid: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            autoSkipPadding: 48,
            maxRotation: 0,
          },
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        htmlLegend: {
          // ID of the container to put the legend in
          containerID: 'fintech-card-01-legend',
        },
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => `${context.parsed.y}%`,
          },
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      maintainAspectRatio: false,
    },
    plugins: [{
      id: 'htmlLegend',
      afterUpdate(c, args, options) {
        const legendContainer = document.getElementById(options.containerID);
        const ul = legendContainer.querySelector('ul');
        if (!ul) return;
        // Remove old legend items
        while (ul.firstChild) {
          ul.firstChild.remove();
        }
        // Reuse the built-in legendItems generator
        const items = c.options.plugins.legend.labels.generateLabels(c);
        items.forEach((item) => {
          const li = document.createElement('li');
          li.style.marginLeft = '12px';
          // Button element
          const button = document.createElement('button');
          button.style.display = 'inline-flex';
          button.style.alignItems = 'center';
          button.style.opacity = item.hidden ? '.3' : '';
          button.onclick = () => {
            c.setDatasetVisibility(item.datasetIndex, !c.isDatasetVisible(item.datasetIndex));
            c.update();
          };
          // Color box
          const box = document.createElement('span');
          box.style.display = 'block';
          box.style.width = '12px';
          box.style.height = '12px';
          box.style.borderRadius = '9999px';
          box.style.marginRight = '8px';
          box.style.borderWidth = '3px';
          box.style.borderColor = c.data.datasets[item.datasetIndex].borderColor;
          box.style.pointerEvents = 'none';
          // Label
          const label = document.createElement('span');
          label.style.color = '#64748b';
          label.style.fontSize = '0.875rem';
          label.style.lineHeight = '1.5715';
          const labelText = document.createTextNode(item.text);
          label.appendChild(labelText);
          li.appendChild(button);
          button.appendChild(box);
          button.appendChild(label);
          ul.appendChild(li);
        });
      },
    }],
  });
};
fintechCard01();

// Init #fintech-03 chart
const fintechCard03 = () => {
  const ctx = document.getElementById('fintech-card-03');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        '12-01-2020', '01-01-2021', '02-01-2021',
        '03-01-2021', '04-01-2021', '05-01-2021',
      ],
      datasets: [
        // Indigo bars
        {
          label: 'Inflow',
          data: [
            800, 2600, 4000, 1200, 3200, 1700,
          ],
          backgroundColor: '#6366f1',
          hoverBackgroundColor: '#4f46e5',
          barPercentage: 0.66,
          categoryPercentage: 0.66,
        },
        // Grey bars
        {
          label: 'Outflow',
          data: [
            2800, 1700, 900, 2900, 1950, 3100,
          ],
          backgroundColor: '#cbd5e1',
          hoverBackgroundColor: '#94a3b8',
          barPercentage: 0.66,
          categoryPercentage: 0.66,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 12,
          bottom: 16,
          left: 20,
          right: 20,
        },
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
          },
          ticks: {
            maxTicksLimit: 5,
            callback: (value) => formatValue(value),
          },
        },
        x: {
          type: 'time',
          time: {
            parser: 'MM-DD-YYYY',
            unit: 'month',
            displayFormats: {
              month: 'MMM YY',
            },
          },
          grid: {
            display: false,
            drawBorder: false,
          },
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        htmlLegend: {
          // ID of the container to put the legend in
          containerID: 'fintech-card-03-legend',
        },
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.y),
          },
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      animation: {
        duration: 200,
      },
      maintainAspectRatio: false,
    },
    plugins: [{
      id: 'htmlLegend',
      afterUpdate(c, args, options) {
        const legendContainer = document.getElementById(options.containerID);
        const ul = legendContainer.querySelector('ul');
        if (!ul) return;
        // Remove old legend items
        while (ul.firstChild) {
          ul.firstChild.remove();
        }
        // Reuse the built-in legendItems generator
        const items = c.options.plugins.legend.labels.generateLabels(c);
        items.forEach((item) => {
          const li = document.createElement('li');
          li.style.marginRight = '16px';
          // Button element
          const button = document.createElement('button');
          button.style.display = 'inline-flex';
          button.style.alignItems = 'center';
          button.style.opacity = item.hidden ? '.3' : '';
          button.onclick = () => {
            c.setDatasetVisibility(item.datasetIndex, !c.isDatasetVisible(item.datasetIndex));
            c.update();
          };
          // Color box
          const box = document.createElement('span');
          box.style.display = 'block';
          box.style.width = '12px';
          box.style.height = '12px';
          box.style.borderRadius = '9999px';
          box.style.marginRight = '8px';
          box.style.borderWidth = '3px';
          box.style.borderColor = item.fillStyle;
          box.style.pointerEvents = 'none';
          // Label
          const label = document.createElement('span');
          label.style.color = '#64748b';
          label.style.fontSize = '0.875rem';
          label.style.lineHeight = '1.5715';
          const labelText = document.createTextNode(item.text);
          label.appendChild(labelText);
          li.appendChild(button);
          button.appendChild(box);
          button.appendChild(label);
          ul.appendChild(li);
        });
      },
    }],
  });
};
fintechCard03();

// Init #fintech-04 chart
const images = [
  './images/company-icon-01.svg', // Revolut
  './images/company-icon-02.svg', // HSBC
  './images/company-icon-03.svg', // Qonto
  './images/company-icon-04.svg'  // N26
];

const fintechCard04 = () => {
  const ctx = document.getElementById('fintech-card-04');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        '02-01-2021', '03-01-2021', '04-01-2021', '05-01-2021',
      ],
      datasets: [
        // Indigo bars
        {
          label: 'Inflow',
          data: [
            4100, 1900, 2700, 3900,
          ],
          backgroundColor: '#6366f1',
          hoverBackgroundColor: '#4f46e5',
          categoryPercentage: 0.66,
        },
        // Gray bars
        {
          label: 'Outflow',
          data: [
            2000, 1000, 1100, 2600,
          ],
          backgroundColor: '#cbd5e1',
          hoverBackgroundColor: '#94a3b8',
          categoryPercentage: 0.66,
        },
      ],
    },
    options: {
      indexAxis: 'y',
      layout: {
        padding: {
          top: 12,
          bottom: 16,
          left: 72,
          right: 20,
        },
      },
      scales: {
        y: {
          grid: {
            display: false,
            drawBorder: false,
            drawTicks: false,
          },
          ticks: {
            display: false,
          },
        },
        x: {
          grid: {
            drawBorder: false,
          },
          ticks: {
            maxTicksLimit: 3,
            align: 'end',
            callback: (value) => formatValue(value),
          },
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        htmlLegend: {
          // ID of the container to put the legend in
          containerID: 'fintech-card-04-legend',
        },
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.x),
          },
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      animation: {
        duration: 200,
      },
      maintainAspectRatio: false,
    },
    plugins: [{
      id: 'htmlLegend',
      afterUpdate(c, args, options) {
        const legendContainer = document.getElementById(options.containerID);
        const ul = legendContainer.querySelector('ul');
        if (!ul) return;
        // Remove old legend items
        while (ul.firstChild) {
          ul.firstChild.remove();
        }
        // Reuse the built-in legendItems generator
        const items = c.options.plugins.legend.labels.generateLabels(c);
        items.forEach((item) => {
          const li = document.createElement('li');
          li.style.marginRight = '16px';
          // Button element
          const button = document.createElement('button');
          button.style.display = 'inline-flex';
          button.style.alignItems = 'center';
          button.style.opacity = item.hidden ? '.3' : '';
          button.onclick = () => {
            c.setDatasetVisibility(item.datasetIndex, !c.isDatasetVisible(item.datasetIndex));
            c.update();
          };
          // Color box
          const box = document.createElement('span');
          box.style.display = 'block';
          box.style.width = '12px';
          box.style.height = '12px';
          box.style.borderRadius = '9999px';
          box.style.marginRight = '8px';
          box.style.borderWidth = '3px';
          box.style.borderColor = item.fillStyle;
          box.style.pointerEvents = 'none';
          // Label
          const label = document.createElement('span');
          label.style.color = '#64748b';
          label.style.fontSize = '0.875rem';
          label.style.lineHeight = '1.5715';
          const labelText = document.createTextNode(item.text);
          label.appendChild(labelText);
          li.appendChild(button);
          button.appendChild(box);
          button.appendChild(label);
          ul.appendChild(li);
        });
      },
      afterDraw(c) {
        const xAxis = c.scales.x;
        const yAxis = c.scales.y;
        yAxis.ticks.forEach((value, index) => {
          const y = yAxis.getPixelForTick(index);
          const image = new Image();
          image.src = images[index];
          c.ctx.drawImage(image, xAxis.left - 52, y - 18);
        });
      },
    }],
  });
};
fintechCard04();

// Init #fintech-07 chart
const fintechCard07 = () => {
  const ctx = document.getElementById('fintech-card-07');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '09-01-2021', '10-01-2021', '11-01-2021',
        '12-01-2021', '01-01-2022', '02-01-2022',
        '03-01-2022', '04-01-2022', '05-01-2022',
        '06-01-2022', '07-01-2022', '08-01-2022',
        '09-01-2022', '10-01-2022', '11-01-2022',
        '12-01-2022', '01-01-2023', '02-01-2023',
        '03-01-2023', '04-01-2023',
      ],
      datasets: [
        // Indigo line
        {
          label: 'Mosaic Portfolio',
          data: [
            1500, 2000, 1800, 1900, 1900, 2400, 2900, 2600, 3900, 2700,
            3500, 3200, 2900, 3500, 3600, 3400, 3900, 3600, 4100, 4100,
          ],
          borderColor: '#6366f1',
          fill: true,
          backgroundColor: `rgba(${hexToRGB('#3b82f6')}, 0.08)`,
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#6366f1',
          clip: 20,
        },
        // Gray line
        {
          label: 'Expected Return',
          data: [
            2000, 2100, 2200, 2300, 2400, 2500, 2600, 2700, 2800, 2900,
            3000, 3100, 3200, 3300, 3400, 3500, 3600, 3700, 3800, 3900,
          ],
          borderColor: '#cbd5e1',
          fill: false,
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#cbd5e1',
          clip: 20,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 12,
          bottom: 16,
          left: 20,
          right: 20,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
          },
          ticks: {
            maxTicksLimit: 7,
            callback: (value) => formatValue(value),
          },
        },
        x: {
          type: 'time',
          time: {
            parser: 'MM-DD-YYYY',
            unit: 'month',
            displayFormats: {
              month: 'MMM YY',
            },
          },
          grid: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            autoSkipPadding: 48,
            maxRotation: 0,
          },
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.y),
          },
        },
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      maintainAspectRatio: false,
    },
  });
};
fintechCard07();

// Init #fintech-08 chart
const fintechCard08 = () => {
  const ctx = document.getElementById('fintech-card-08');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '2010', 'Age 65',
      ],
      datasets: [
        // Dark green line
        {
          label: 'Growth 1',
          data: [
            0, 3500000,
          ],
          borderColor: '#10b981',
          fill: true,
          backgroundColor: `rgba(${hexToRGB('#10b981')}, 0.08)`,
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#10b981',
          clip: 20,
        },
        // Light green line
        {
          label: 'Growth 2',
          data: [
            0, 2000000,
          ],
          borderColor: '#a7f3d0',
          fill: false,
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#a7f3d0',
          clip: 20,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 12,
          bottom: 16,
          left: 20,
          right: 20,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
          },
          ticks: {
            maxTicksLimit: 7,
            callback: (value) => formatValue(value),
          },
        },
        x: {
          grid: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            autoSkipPadding: 48,
            maxRotation: 0,
            align: 'end',
          },
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.y),
          },
        },
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      maintainAspectRatio: false,
    },
  });
};
fintechCard08();

// Init #fintech-09 chart
const fintechCard09 = () => {
  const ctx = document.getElementById('fintech-card-09');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Cash', 'Commodities', 'Bonds', 'Stock'],
      datasets: [
        {
          label: 'Sessions By Device',
          data: [
            12, 13, 10, 65,
          ],
          backgroundColor: [
            '#34d399',
            '#fbbf24',
            '#38bdf8',
            '#6366f1',
          ],
          hoverBackgroundColor: [
            '#10b981',
            '#f59e0b',
            '#0ea5e9',
            '#4f46e5',
          ],
          borderWidth: 0,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 4,
          bottom: 4,
          left: 24,
          right: 24,
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        htmlLegend: {
          // ID of the container to put the legend in
          containerID: 'fintech-card-09-legend',
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      animation: {
        duration: 200,
      },
      maintainAspectRatio: false,
    },
    plugins: [{
      id: 'htmlLegend',
      afterUpdate(c, args, options) {
        const legendContainer = document.getElementById(options.containerID);
        const ul = legendContainer.querySelector('ul');
        if (!ul) return;
        // Remove old legend items
        while (ul.firstChild) {
          ul.firstChild.remove();
        }
        // Reuse the built-in legendItems generator
        const items = c.options.plugins.legend.labels.generateLabels(c);
        items.forEach((item) => {
          const li = document.createElement('li');
          li.style.margin = '6px';
          // Button element
          const button = document.createElement('button');
          button.style.display = 'inline-flex';
          button.style.alignItems = 'center';
          button.style.opacity = item.hidden ? '.3' : '';
          button.onclick = () => {
            c.toggleDataVisibility(item.index, !item.index);
            c.update();
          };
          // Color box
          const box = document.createElement('span');
          box.style.display = 'block';
          box.style.width = '12px';
          box.style.height = '12px';
          box.style.borderRadius = '9999px';
          box.style.marginRight = '6px';
          box.style.borderWidth = '3px';
          box.style.borderColor = item.fillStyle;
          box.style.pointerEvents = 'none';
          // Label
          const label = document.createElement('span');
          label.style.color = '#64748b';
          label.style.fontSize = '0.875rem';
          label.style.lineHeight = '1.5715';
          const labelText = document.createTextNode(item.text);
          label.appendChild(labelText);
          li.appendChild(button);
          button.appendChild(box);
          button.appendChild(label);
          ul.appendChild(li);
        });
      },
    }],
  });
};
fintechCard09();

// Init #fintech-10 chart
const fintechCard10 = () => {
  const ctx = document.getElementById('fintech-card-10');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '12-01-2020', '01-01-2021', '02-01-2021',
        '03-01-2021', '04-01-2021', '05-01-2021',
        '06-01-2021', '07-01-2021', '08-01-2021',
        '09-01-2021', '10-01-2021', '11-01-2021',
        '12-01-2021', '01-01-2022', '02-01-2022',
        '03-01-2022', '04-01-2022', '05-01-2022',
        '06-01-2022', '07-01-2022', '08-01-2022',
        '09-01-2022', '10-01-2022', '11-01-2022',
        '12-01-2022', '01-01-2023',
      ],
      datasets: [
        // Line
        {
          data: [
            732, 610, 610, 504, 504, 504, 349,
            349, 504, 342, 504, 610, 391, 192,
            154, 273, 191, 191, 126, 263, 349,
            252, 323, 322, 270, 232,
          ],
          fill: true,
          backgroundColor: `rgba(${hexToRGB('#f43f5e')}, 0.08)`,
          borderColor: '#f43f5e',
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#f43f5e',
          clip: 20,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 16,
          bottom: 16,
          left: 20,
          right: 20,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
            drawTicks: false,
          },
          ticks: {
            maxTicksLimit: 2,
            display: false,
          },
        },
        x: {
          type: 'time',
          time: {
            parser: 'MM-DD-YYYY',
            unit: 'month',
          },
          display: false,
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.y),
          },
        },
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      maintainAspectRatio: false,
    },
  });
};
fintechCard10();

// Init #fintech-11 chart
const fintechCard11 = () => {
  const ctx = document.getElementById('fintech-card-11');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '12-01-2020', '01-01-2021', '02-01-2021',
        '03-01-2021', '04-01-2021', '05-01-2021',
        '06-01-2021', '07-01-2021', '08-01-2021',
        '09-01-2021', '10-01-2021', '11-01-2021',
        '12-01-2021', '01-01-2022', '02-01-2022',
        '03-01-2022', '04-01-2022', '05-01-2022',
        '06-01-2022', '07-01-2022', '08-01-2022',
        '09-01-2022', '10-01-2022', '11-01-2022',
        '12-01-2022', '01-01-2023',
      ],
      datasets: [
        // Line
        {
          data: [
            222, 222, 226, 271, 365, 365, 238,
            324, 288, 206, 324, 324, 500, 409,
            409, 273, 232, 273, 500, 570, 767,
            808, 685, 767, 685, 685,
          ],
          fill: true,
          backgroundColor: `rgba(${hexToRGB('#10b981')}, 0.08)`,
          borderColor: '#10b981',
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#10b981',
          clip: 20,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 16,
          bottom: 16,
          left: 20,
          right: 20,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
            drawTicks: false,
          },
          ticks: {
            maxTicksLimit: 2,
            display: false,
          },
        },
        x: {
          type: 'time',
          time: {
            parser: 'MM-DD-YYYY',
            unit: 'month',
          },
          display: false,
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.y),
          },
        },
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      maintainAspectRatio: false,
    },
  });
};
fintechCard11();

// Init #fintech-12 chart
const fintechCard12 = () => {
  const ctx = document.getElementById('fintech-card-12');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '12-01-2020', '01-01-2021', '02-01-2021',
        '03-01-2021', '04-01-2021', '05-01-2021',
        '06-01-2021', '07-01-2021', '08-01-2021',
        '09-01-2021', '10-01-2021', '11-01-2021',
        '12-01-2021', '01-01-2022', '02-01-2022',
        '03-01-2022', '04-01-2022', '05-01-2022',
        '06-01-2022', '07-01-2022', '08-01-2022',
        '09-01-2022', '10-01-2022', '11-01-2022',
        '12-01-2022', '01-01-2023',
      ],
      datasets: [
        // Line
        {
          data: [
            540, 466, 540, 466, 385, 432, 334,
            334, 289, 289, 200, 289, 222, 289,
            289, 403, 554, 304, 289, 270, 134,
            270, 829, 644, 688, 664,
          ],
          fill: true,
          backgroundColor: `rgba(${hexToRGB('#10b981')}, 0.08)`,
          borderColor: '#10b981',
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#10b981',
          clip: 20,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 16,
          bottom: 16,
          left: 20,
          right: 20,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
            drawTicks: false,
          },
          ticks: {
            maxTicksLimit: 2,
            display: false,
          },
        },
        x: {
          type: 'time',
          time: {
            parser: 'MM-DD-YYYY',
            unit: 'month',
          },
          display: false,
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.y),
          },
        },
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      maintainAspectRatio: false,
    },
  });
};
fintechCard12();

// Init #fintech-13 chart
const fintechCard13 = () => {
  const ctx = document.getElementById('fintech-card-13');
  if (!ctx) return;
  // eslint-disable-next-line no-unused-vars
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '12-01-2020', '01-01-2021', '02-01-2021',
        '03-01-2021', '04-01-2021', '05-01-2021',
        '06-01-2021', '07-01-2021', '08-01-2021',
        '09-01-2021', '10-01-2021', '11-01-2021',
        '12-01-2021', '01-01-2022', '02-01-2022',
        '03-01-2022', '04-01-2022', '05-01-2022',
        '06-01-2022', '07-01-2022', '08-01-2022',
        '09-01-2022', '10-01-2022', '11-01-2022',
        '12-01-2022', '01-01-2023',
      ],
      datasets: [
        // Line
        {
          data: [
            245, 288, 332, 404, 404, 314, 314,
            314, 314, 314, 234, 314, 234, 234,
            314, 314, 314, 388, 314, 202, 202,
            202, 202, 514, 720, 642,
          ],
          fill: true,
          backgroundColor: `rgba(${hexToRGB('#10b981')}, 0.08)`,
          borderColor: '#10b981',
          borderWidth: 2,
          tension: 0,
          pointRadius: 0,
          pointHoverRadius: 3,
          pointBackgroundColor: '#10b981',
          clip: 20,
        },
      ],
    },
    options: {
      layout: {
        padding: {
          top: 16,
          bottom: 16,
          left: 20,
          right: 20,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
            drawTicks: false,
          },
          ticks: {
            maxTicksLimit: 2,
            display: false,
          },
        },
        x: {
          type: 'time',
          time: {
            parser: 'MM-DD-YYYY',
            unit: 'month',
          },
          display: false,
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            title: () => false, // Disable tooltip title
            label: (context) => formatValue(context.parsed.y),
          },
        },
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: 'nearest',
      },
      maintainAspectRatio: false,
    },
  });
};
fintechCard13();

// Init #fintech-14 chart
const fintechCard14 = () => {
  const miniCharts = [
    // Twitter
    {
      selector: 'fintech-card-14-a',
      data: [
        540, 466, 540, 466, 385, 432, 334,
        334, 289, 289, 200, 289, 222, 289,
        289, 403, 554, 304, 289, 270, 134,
        270, 829, 644, 688, 664,
      ],
      growth: true,
    },
    // Facebook
    {
      selector: 'fintech-card-14-b',
      data: [
        245, 288, 332, 404, 404, 314, 314,
        314, 314, 314, 234, 314, 234, 234,
        314, 314, 314, 388, 314, 202, 202,
        202, 202, 514, 720, 642,
      ],
      growth: true,
    },
    // Google
    {
      selector: 'fintech-card-14-c',
      data: [
        732, 610, 610, 504, 504, 504, 349,
        349, 504, 342, 504, 610, 391, 192,
        154, 273, 191, 191, 126, 263, 349,
        252, 323, 322, 270, 232,
      ],
      growth: false,
    },
    // Apple
    {
      selector: 'fintech-card-14-d',
      data: [
        222, 222, 226, 271, 365, 365, 238,
        324, 288, 206, 324, 324, 500, 409,
        409, 273, 232, 273, 500, 570, 767,
        808, 685, 767, 685, 685,
      ],
      growth: true,
    },
    // Coinbase
    {
      selector: 'fintech-card-14-e',
      data: [
        632, 510, 610, 404, 504, 404, 449,
        349, 404, 542, 404, 410, 491, 392,
        254, 273, 291, 191, 226, 363, 449,
        252, 223, 222, 170, 132,
      ],
      growth: false,
    },
  ];

  miniCharts.forEach((miniChart) => {
    const ctx = document.getElementById(miniChart.selector);
    if (!ctx) return;
    // eslint-disable-next-line no-unused-vars
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [
          '12-01-2020', '01-01-2021', '02-01-2021',
          '03-01-2021', '04-01-2021', '05-01-2021',
          '06-01-2021', '07-01-2021', '08-01-2021',
          '09-01-2021', '10-01-2021', '11-01-2021',
          '12-01-2021', '01-01-2022', '02-01-2022',
          '03-01-2022', '04-01-2022', '05-01-2022',
          '06-01-2022', '07-01-2022', '08-01-2022',
          '09-01-2022', '10-01-2022', '11-01-2022',
          '12-01-2022', '01-01-2023',
        ],
        datasets: [
          // Line
          {
            data: miniChart.data,
            // eslint-disable-next-line max-len
            borderColor: miniChart.growth ? '#10b981' : '#f43f5e',
            borderWidth: 2,
            tension: 0,
            pointRadius: 0,
            pointHoverRadius: 3,
            // eslint-disable-next-line max-len
            pointBackgroundColor: miniChart.growth ? '#10b981' : '#f43f5e',
            clip: 20,
          },
        ],
      },
      options: {
        scales: {
          y: {
            display: false,
            beginAtZero: true,
          },
          x: {
            type: 'time',
            time: {
              parser: 'MM-DD-YYYY',
              unit: 'month',
            },
            display: false,
          },
        },
        plugins: {
          tooltip: {
            callbacks: {
              title: () => false, // Disable tooltip title
              label: (context) => formatValue(context.parsed.y),
            },
          },
          legend: {
            display: false,
          },
        },
        interaction: {
          intersect: false,
          mode: 'nearest',
        },
        maintainAspectRatio: false,
      },
    });
  });
};
fintechCard14(); 