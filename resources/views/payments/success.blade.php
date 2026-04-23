<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - The Curated Archive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0B1120',
                            accent: '#A67C00',
                            accentHover: '#8C6800',
                            surface: '#FFFFFF',
                            bg: '#FAFAFA'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-bg font-sans text-gray-800 antialiased flex flex-col min-h-screen">

    <div class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white p-8 md:p-12 shadow-sm border border-gray-100 max-w-lg w-full text-center rounded-sm">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h1 class="text-3xl font-serif text-gray-900 mb-4">Payment Successful!</h1>
            
            <p class="text-gray-600 mb-8 leading-relaxed">
                Thank you. Your payment was successful. An account has been created for you to track your project, and details to login have been sent to your email.
            </p>

            <a href="{{ route('login') }}" class="inline-block w-full sm:w-auto bg-brand-dark text-white px-8 py-3 tracking-widest font-bold uppercase text-sm rounded hover:bg-gray-800 transition-colors">
                Go to Login
            </a>
        </div>
    </div>

</body>
</html>
