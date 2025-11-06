  <!-- Sidebar -->
  <aside x-data="{ open: false }" @toggle-sidebar.window="open = !open" id="logo-sidebar m-"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
      :class="{ '-translate-x-full': !open, 'translate-x-0': open }" aria-label="Sidebar">

      <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
          <ul class="space-y-2 font-medium">
              <!-- Dashboard -->
              <li>
                  <a href="{{ route('dashboard') }}"
                      class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-orange-100 text-orange-700 dark:bg-orange-600' : '' }}">
                      <i
                          class="fas fa-tachometer-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                          <svg id="Graph" width="24" height="24" viewBox="0 0 24 24" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M11.2232 5.24146H10.7232C9.08523 5.24146 7.49923 5.72245 6.13723 6.63245C4.77523 7.54245 3.72423 8.82345 3.09723 10.3365C2.47023 11.8495 2.30823 13.4995 2.62823 15.1055C2.94723 16.7135 3.72823 18.1745 4.88723 19.3315C6.04423 20.4895 7.50523 21.2705 9.11223 21.5905C9.64823 21.6975 10.1882 21.7505 10.7272 21.7505C11.8032 21.7505 12.8722 21.5385 13.8812 21.1205C15.3942 20.4935 16.6762 19.4435 17.5852 18.0815C18.4952 16.7195 18.9762 15.1345 18.9762 13.4955V12.9955H11.2232V5.24146Z"
                                  fill="#fff"></path>
                              <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                  d="M19.6138 4.66676C18.0558 3.10776 15.9828 2.24976 13.7778 2.24976H13.2778V11.0028H22.0318V10.5028C22.0318 8.29876 21.1728 6.22676 19.6138 4.66676Z"
                                  fill="#fff"></path>
                          </svg></i>
                      <span class="ms-3">Dashboard</span>
                  </a>
              </li>

              <li>
                  <a href="{{ route('admin.products.index') }}"
                      class="flex items-center p-2 rounded-lg transition duration-150
                  {{ request()->routeIs('admin.products.*') ? 'bg-orange-100 text-white dark:bg-orange-600' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                      <i
                          class="fas fa-tachometer-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                              stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                          </svg>
                      </i>
                      <span class="ms-3">Produtos</span>
                  </a>
              </li>

              <li>
                  <a href="{{ route('admin.categories.index') }}"
                      class="flex items-center p-2 rounded-lg transition duration-150
                {{ request()->routeIs('admin.categories.*') ? 'bg-orange-100 text-white dark:bg-orange-600' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                      <i
                          class="fas fa-tachometer-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                          <svg id="Category" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                              xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                              <title>Iconly/Two-tone/Category</title>
                              <g id="Iconly/Two-tone/Category" stroke="none" stroke-width="1.5" fill="none"
                                  fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                                  <g id="Category" transform="translate(2.999141, 3.000000)" stroke="#fff"
                                      stroke-width="1.5">
                                      <path
                                          d="M0.000858865205,3.5 C0.000858865205,0.874787053 0.0289681101,0 3.50085887,0 C6.9727494,0 7.00085887,0.874787053 7.00085887,3.5 C7.00085887,6.12521295 7.01193168,7 3.50085887,7 C-0.010214169,7 0.000858865205,6.12521295 0.000858865205,3.5 Z"
                                          id="Stroke-1"></path>
                                      <path
                                          d="M11.0008589,3.5 C11.0008589,0.874787053 11.0289681,0 14.5008589,0 C17.9727494,0 18.0008589,0.874787053 18.0008589,3.5 C18.0008589,6.12521295 18.0119317,7 14.5008589,7 C10.9897858,7 11.0008589,6.12521295 11.0008589,3.5 Z"
                                          id="Stroke-3" opacity="0.400000006"></path>
                                      <path
                                          d="M0.000858865205,14.5 C0.000858865205,11.8747871 0.0289681101,11 3.50085887,11 C6.9727494,11 7.00085887,11.8747871 7.00085887,14.5 C7.00085887,17.1252129 7.01193168,18 3.50085887,18 C-0.010214169,18 0.000858865205,17.1252129 0.000858865205,14.5 Z"
                                          id="Stroke-5"></path>
                                      <path
                                          d="M11.0008589,14.5 C11.0008589,11.8747871 11.0289681,11 14.5008589,11 C17.9727494,11 18.0008589,11.8747871 18.0008589,14.5 C18.0008589,17.1252129 18.0119317,18 14.5008589,18 C10.9897858,18 11.0008589,17.1252129 11.0008589,14.5 Z"
                                          id="Stroke-7"></path>
                                  </g>
                              </g>
                          </svg></i>

                      <span class="ms-3">Categorias</span>
                  </a>
              </li>
              <li>
                  <a href="{{ route('admin.shipping-profiles.index') }}"
                      class="flex items-center p-2 rounded-lg transition duration-150
              {{ request()->routeIs('admin.shipping-profiles.*')
                  ? 'bg-orange-100 text-white dark:bg-orange-600'
                  : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                      <i class="w-6 h-6 flex items-center justify-center">
                        <img 
                            src="{{ asset('img/box.png') }}" 
                            alt="Ícone de Caixa" 
                            class="w-5 h-5 object-contain brightness-0 dark:brightness-200 group-hover:brightness-100 transition duration-200"
                        >
                    </i>
                    

                      <span class="ms-3">Entregas</span>
                  </a>
              </li>


          </ul>
      </div>
  </aside>



  <script>
      document.addEventListener('alpine:init', () => {
          Alpine.data('sidebar', () => ({
              open: window.innerWidth >= 640,

              init() {
                  this.$el.querySelectorAll('a').forEach(link => {
                      link.addEventListener('click', () => {
                          if (window.innerWidth < 640) {
                              this.open = false;
                          }
                      });
                  });
              }
          }));
      });
  </script>
