using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;
public class CuaHang : MonoBehaviour
{
    public Text stt;
    public Text id;
    public Text tenCuaHang;
    public Text trangThai;
    public Text sdt;
    public Text diaChi;

    public void Xoa()
    {
        QuanLyCuaHang.instance.CapNhatSTT(this);
    }

    public void Active()
    {
        if (trangThai.text == "Đã kích hoạt")
        {
            trangThai.text = "Ngưng kích hoạt";
            StartCoroutine(ChinhSuaCuaHang(0));
        }
        else
        {
            trangThai.text = "Đã kích hoạt";
            StartCoroutine(ChinhSuaCuaHang(1));
        }
    }

    IEnumerator ChinhSuaCuaHang(int trangthai)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("activate", "");

        form.AddField("store_id", id.text);


        // * URL
        string url = "http://localhost/php/store.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Chỉnh sửa cửa hàng thành công");
                Manager.instance.CapNhatCuaHang();
                QuanLyCuaHang.instance.CapNhatDSCH();
            }
            else // * Request Fail
            {
                Debug.Log("Chỉnh sửa cửa hàng thất bại -> Error : " + result[0]);
            }
        }
    }
}
