import prettier from "prettier";
import fs from "fs";
import vuePlugin from "prettier-plugin-vue";

async function main() {
  const filePath = "resources/js/pages/DashboardPage.vue";

  // Baca file
  const code = fs.readFileSync(filePath, "utf-8");

  // Ambil config Prettier (async)
  const config = await prettier.resolveConfig(filePath);

  // Format kode → sekarang await karena async
  const formatted = await prettier.format(code, {
    ...config,
    parser: "vue",
    plugins: [vuePlugin],
  });

  // Tulis kembali ke file
  fs.writeFileSync(filePath, formatted, "utf-8");
  console.log("File berhasil diformat!");
}

main();
