/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./public/*.{html,php,js}"],
  theme: {
    extend: {
      zIndex: {
        '10': '10',
      },
      padding: {
        '24px': '24px',
      },
      boxShadow: {
        'custom-hover': '5px -5px 1px #FF7100',
        'custom-inner': 'inset 5px -5px 5px #FF7100',
        'custom-border-hover': '4.5px -4.5px 0 #000000, 5px -5px 0 1px #f28d3c',
        'custom-bg-border-hover': '4.5px -4.5px 0 #f9d4b3, 5px -5px 0 1px #f28d3c',
        'custom-hover-table': '5px -5px 1px rgba(0, 0, 0, 0.8)',   
      },
      screens: {
        '2xl': '1360px',
        '2md': '870px',
      },
      plugins: [
        function({ addBase, theme }) {
          addBase({
            'input[type="file"]::-webkit-file-upload-button': {
              visibility: 'hidden',
            },
          });
        },
      ],
  },
  plugins: [],
}
}

