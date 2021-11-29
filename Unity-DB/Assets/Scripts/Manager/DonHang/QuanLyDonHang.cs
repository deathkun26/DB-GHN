using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class QuanLyDonHang : ButtonHandler
{
    public static QuanLyDonHang instance;

    public GameObject donHangPrefabs;

    public Transform container;

    public Dropdown trangthai;

    public InputField tgianBatDau;
    public InputField tgianKetThuc;

    public GameObject lenDonHang;

    private void Awake()
    {
        if (instance == null)
            instance = this;
    }

    public override void PointerDownHandler()
    {
        Load();
    }

    public void Load()
    {
        Debug.Log("Loading");

        Manager.ClearContainer(container, clearHeader: true);

        StartCoroutine(SendRequestHandler(trangthai.value - 1));

    }

    IEnumerator SendRequestHandler(int trangthai)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("tracuu", 1);
        form.AddField("store_id", Manager.instance.cuaHangCrt.Split('-')[1].Remove(0, 1));
        Debug.Log("store id : " + Manager.instance.cuaHangCrt.Split('-')[1].Remove(0, 1));
        form.AddField("status", trangthai);

        form.AddField("time_from", tgianBatDau.text);
        form.AddField("time_to", tgianKetThuc.text);

        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            //Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');


            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++) // ! Bỏ \n cuối
                {
                    //Debug.Log(result[i]);
                    string[] data = result[i].Split('\t');
                    GameObject donHangObj = Instantiate(donHangPrefabs, container);
                    DonHang donHang = donHangObj.GetComponent<DonHang>();
                    donHang.stt.text = i.ToString();
                    donHang.maVanDon.text = data[0];
                    donHang.nguoiNhan.text = data[1];
                    donHang.sdt_DiaChi.text = data[2] + " - " + data[3];
                    if (data[4] == "0")
                    {
                        donHang.trangThai.text = "Đơn nháp";
                    }
                    else if (data[4] == "1")
                    {
                        donHang.trangThai.text = "Đang xử lý";

                    }
                    else if (data[4] == "2")
                    {
                        donHang.trangThai.text = "Đang giao";

                    }
                    else if (data[4] == "3")
                    {
                        donHang.trangThai.text = "Hoàn thành";
                    }
                    else
                        Debug.Log("Sai code trạng thái");

                    donHang.phiDichVu.text = "50000";// data[5];
                    donHang.Display();
                }
            }
            else // * Request Fail
            {
                Debug.Log("Lấy danh sách đơn hàng -> Error : " + result[0]);
            }
        }
    }

}


